package serveur;

import java.io.BufferedInputStream;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.Socket;
import java.net.SocketException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.SimpleDateFormat;
import java.util.Date;

import javax.swing.JTextArea;

import autre.Constantes;

/**
 * Classe s'occupant des clients connectés
 * Chaque client est traité dans un nouveau thread dans une instance de cette classe
 */

public class ClientProcessor implements Runnable {

	private Socket sock;
	private PrintWriter writer = null;
	private BufferedInputStream reader = null;
	private JTextArea textArea = null;
	private SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy [HH:mm:ss]");
	
	private Connection c = dbConnect();
	
	public ClientProcessor(Socket sock, JTextArea textArea) {
		this.textArea = textArea;
		this.sock = sock;
	}
	
	// Traitement lancé dans un thread séparé
	@Override
	public void run() {
		boolean closeConnection = false;
		try {
			writer = new PrintWriter(sock.getOutputStream());
			reader = new BufferedInputStream(sock.getInputStream());
		}catch(IOException e) {
			e.printStackTrace();
		}
		
		// Boucler tant que la connexion n'est pas fermée
		while(!sock.isClosed()) {
			try {
				// Attente de la demande du client
				String response = read();
				String[] parsed_resp = response.split(Constantes.SEP, 0);
				
				String toSend = "";
				
				// Traitement de la demande du client
				switch(parsed_resp[0].toLowerCase()) {
					case "close":
						toSend = "Communication terminée.\n";
						closeConnection = true;
						break;
						
					case "connect":
						if(connect(parsed_resp[1], parsed_resp[2])) {
							toSend = "Connexion au compte réussie.\n";
						}else {
							toSend = "";
						}
						break;
						
					case "get":
						try {
							toSend = get(parsed_resp[1]);
						} catch (SQLException e) {
							e.printStackTrace();
							toSend = "Erreur";
						}
						break;
						
					case "del":
						try {
							toSend = del(parsed_resp[1], parsed_resp[2]);
						} catch(SQLException e) {
							e.printStackTrace();
							toSend = "Erreur";
						}
						break;
						
					case "add":
						try {
							toSend = add(parsed_resp);
						}catch(SQLException e) {
							e.printStackTrace();
							toSend = "Erreur";
						}
						break;
						
					case "getw":
						try {
							toSend = getW(parsed_resp[1], parsed_resp[2]);
						} catch(SQLException e) {
							e.printStackTrace();
							toSend = "Erreur";
						}
						break;
						
					case "update":
						try {
							toSend = update(parsed_resp);
						} catch(SQLException e) {
							e.printStackTrace();
							toSend = "Erreur";
						}
						break;
						
					default:
						toSend = "Commande inconnue.\n";
						break;
				}
				
				// Permet de garder une trace des échanges
				debug(sock.getRemoteSocketAddress() + " -> " + response + "\n");
				debug(sock.getRemoteSocketAddress() + " <- " + toSend + "\n");
				// Envoie de la réponse au client
				writer.write(toSend);
				writer.flush();
				
				// Fermeture de la connexion en douceur
				if (closeConnection) {
					debug("Déconnexion de" + sock.getRemoteSocketAddress() + "\n");
					writer = null;
					reader = null;
					sock.close();
					dbClose(c);
					break;
				}
			} catch(SocketException e) {
				// Le client s'est déconnecter violemment
				debug("Connexion interrompue avec " + sock.getRemoteSocketAddress() + "\n");
				dbClose(c);
				break;
			} catch(IOException e) {
				e.printStackTrace();
			}
		}
	}
	
	// Fonction qui convertit le flux d'octets en provenance du client en String
	private String read() throws IOException{
		String response = "";
		int stream;
		byte[] b = new byte[4096];
		stream = reader.read(b);
		response = new String(b, 0, stream);
		
		return response;
	}
	
	// Affiche l'heure et une commande ou une information de connexion pour les logs
	private void debug(String s) {
		if(textArea != null) {
			textArea.append(formatter.format(new Date()) + " " + s);
		}else {
			System.out.println(s);
		}
	}
	
	// Connexion à la bd
	private Connection dbConnect() {
		try {
			Class.forName("org.postgresql.Driver");
			Connection c = DriverManager.getConnection("jdbc:postgresql://" + Constantes.DB_HOST + ":" + Constantes.DB_PORT + "/" + Constantes.DB_NAME , Constantes.DB_USER, Constantes.DB_PWD);
			c.setAutoCommit(false);
			System.out.println("Connexion à la base de données effectuée.");
			return c;
		} catch (Exception e) {
			e.printStackTrace();
			System.err.println(e.getClass().getName()+": "+e.getMessage());
			System.exit(0);
			return null;
		}
	}
	
	// Ferme la connexion à la bd
	private void dbClose(Connection c) {
		try {
			c.close();
		} catch (SQLException e) {
			e.printStackTrace();
		}
	}
	
	// Vérifie qu'il y a bien un couple identifiant/mdp correspondant pour la connexion
	private boolean connect(String id, String mdp) {
		try {
			Statement stmt = c.createStatement();
			String query = "SELECT identifiant_employe,mdp_employe FROM employe_admin WHERE identifiant_employe='" + id + "' AND mdp_employe='" + mdp + "';";
			ResultSet rs = stmt.executeQuery(query);
						
			return rs.next();
		}catch(Exception e) {
			
		}
		return false;
	}
	
	// Récupère la table correspondant à s
	// Sélectionne toute la table ou juste les colonnes intéressantes
	private String get(String s) throws SQLException {
		String query;
		
		switch(s.toLowerCase()) {
			case "client":
				query = "SELECT no_client,nom_client,prenom_client,sexe_client,date_client,adr_client,ville_client,cp_client,pref_client,identifiant FROM client";
				break;
				
			case "personnel":
				query = "SELECT * FROM employe_liberama";
				break;
				
			case "auteur":
				query = "SELECT * FROM auteur";
				break;
				
			case "editeur":
				query = "SELECT * FROM editeur";
				break;
				
			case "livres":
				query = "SELECT no_livre,nom_livre,nom_auteur,prenom_auteur,nom_editeur,type_livre,prix_livre,popularite FROM livres INNER JOIN auteur ON livres.auteur=auteur.no_auteur INNER JOIN editeur ON livres.editeur=editeur.no_editeur";
				break;
				
			default:
				return "";
		}
		
		Statement stmt = c.createStatement();
		ResultSet rs = stmt.executeQuery(query);
		
		ResultSetMetaData md = rs.getMetaData();
		int nbCol = md.getColumnCount();
		String send = "";
		
		// Fusionne toutes les données en un seul String avec Constantes.SEP
		while(rs.next()) {
			for (int col = 1; col <= nbCol; col++) {	
				Object value = rs.getObject(col);
				send = send + Constantes.SEP + value;
			}
		}
		return send;
	}
	
	// Supprime un enregistrement dans la bd
	private String del(String table, String id) throws SQLException {
		Statement stmt = c.createStatement();
		String query;
		switch(table.toLowerCase()) {
			case "client":
				query = "DELETE FROM client WHERE no_client='"+id+"'";
				break;
			case "auteur":
				query = "DELETE FROM auteur WHERE no_auteur='"+id+"'";
				break;
			case "editeur":
				query = "DELETE FROM editeur WHERE no_editeur='"+id+"'";
				break;
			case "personnel":
				query = "DELETE FROM employe_liberama WHERE no_employe='"+id+"'";
				break;
			case "livres":
				query = "DELETE FROM livres WHERE no_livre='"+id+"'";
				break;
			default:
				return "";	
		}
		stmt.executeUpdate(query);
		c.commit();
		return "Suppression effectuée";
	}
	
	// Ajoute un enregistrement dans la bd
	private String add(String[] s) throws SQLException{
		String query;
		switch(s[1].toLowerCase()) {
			case "client":
				query = "INSERT INTO client VALUES('100'||nextval('client_sequence'),'"+s[2]+"','"+s[3]+"','"+s[4]+"','"+s[5]+"','"+s[6]+"','"+s[7]+"','"+s[8]+"','"+s[9]+"','"+s[10]+"','"+s[11]+"')";
				break;
			case "auteur":
				query = "INSERT INTO auteur VALUES('100'||nextval('auteur_sequence'),'"+s[2]+"','"+s[3]+"','"+s[4]+"','"+s[5]+"')";
				break;
			case "editeur":
				query = "INSERT INTO editeur VALUES('100'||nextval('editeur_sequence'),'"+s[2]+"','"+s[3]+"')";
				break;
			case "personnel":
				query = "INSERT INTO employe_liberama VALUES('100'||nextval('employe_sequence'),'"+s[2]+"','"+s[3]+"','"+s[4]+"','"+s[5]+"','"+s[6]+"')";
				break;
			case "livres":
				query = "INSERT INTO livres VALUES('100'||nextval('livres_sequence'),'"+s[2]+"','"+s[3]+"','"+s[4]+"','"+s[5]+"','"+s[6]+"','"+s[7]+"','null')";
				break;
			default:
				return "";	
		}
		Statement stmt = c.createStatement();
		stmt.executeUpdate(query);
		c.commit();
		return "Insertion effectuée";
	}
	
	// Semblable à get(String) mais ici on utilise le mot clef SQL WHERE pour sélectionner une seule ligne avec sa clé primaire unique
	private String getW(String table, String id) throws SQLException {
		String query;
		String send = "";
		switch(table) {
			case "client":
				query = "SELECT no_client,nom_client,prenom_client,sexe_client,date_client,adr_client,ville_client,cp_client,pref_client,identifiant FROM client WHERE no_client='"+id+"'";
				send = "No;Nom;Prenom;Sexe;Date;Adresse;Ville;Code Postal;Préférences;Identifiant";
				break;
				
			case "auteur":
				query = "SELECT * FROM auteur WHERE no_auteur='"+id+"'";
				send = "No;Nom;Prenom;Sexe;Nationalité";
				break;
				
			case "editeur":
				query = "SELECT * FROM editeur WHERE no_editeur='"+id+"'";
				send = "No;Nom;Adresse";
				break;
				
			case "personnel":
				query = "SELECT * FROM employe_liberama WHERE no_employe='"+id+"'";
				send = "No;Poste;Nom;Prenom;Sexe;Magasin";
				break;
				
			case "livres":
				query = "SELECT no_livre,type_livre,nom_livre,prix_livre,auteur,editeur,popularite FROM livres WHERE no_livre='"+id+"'";
				send = "No;Type;Titre;Prix;No auteur;No editeur;Popularité";
				break;
				
			default:
				return "";
		}
		
		Statement stmt = c.createStatement();
		ResultSet rs = stmt.executeQuery(query);
		
		ResultSetMetaData md = rs.getMetaData();
		int nbCol = md.getColumnCount();
		
		// Fusionne toutes les données en un seul String avec Constantes.SEP
		while(rs.next()) {
			for (int col = 1; col <= nbCol; col++) {	
				Object value = rs.getObject(col);
				send = send + Constantes.SEP + value;
			}
		}
		return send;
	}
	
	// Modifie un enregistrement
	private String update(String[] s) throws SQLException {
		String query;
		switch(s[1].toLowerCase()) {
			case "client":
				query = "UPDATE client SET nom_client='"+s[3]+"',prenom_client='"+s[4]+"',sexe_client='"+s[5]+"',date_client='"+s[6]+"',adr_client='"+s[7]+"',ville_client='"+s[8]+"',cp_client='"+s[9]+"',pref_client='"+s[10]+"',identifiant='"+s[11]+"' WHERE no_client='"+s[2]+"'";
				break;
			case "auteur":
				query = "UPDATE auteur SET nom_auteur='"+s[3]+"',prenom_auteur='"+s[4]+"',sexe_auteur='"+s[5]+"',natio_auteur='"+s[6]+"' WHERE no_auteur='"+s[2]+"'";
				break;
			case "editeur":
				query = "UPDATE editeur SET nom_editeur='"+s[3]+"',adr_editeur='"+s[4]+"' WHERE no_editeur='"+s[2]+"'";
				break;
			case "personnel":
				query = "UPDATE employe_liberama SET poste='"+s[3]+"',nom_employe='"+s[4]+"',prenom_employe='"+s[5]+"',sexe_employe='"+s[6]+"',no_magasin='"+s[7]+"' WHERE no_employe='"+s[2]+"'";
				break;
			case "livres":
				query = "UPDATE livres SET type_livre='"+s[3]+"',nom_livre='"+s[4]+"',prix_livre='"+s[5]+"',auteur='"+s[6]+"',editeur='"+s[7]+"',popularite='"+s[8]+"','null' WHERE no_livre='"+s[2]+"'";
				break;
			default:
				return "";	
		}
		Statement stmt = c.createStatement();
		stmt.executeUpdate(query);
		c.commit();
		return "Update effectué";
	}

}
