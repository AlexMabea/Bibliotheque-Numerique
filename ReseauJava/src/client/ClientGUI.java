package client;

import java.awt.Component;
import java.awt.Dimension;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTabbedPane;
import javax.swing.JTable;
import javax.swing.JTextField;

import autre.Constantes;

/**
 * Classe graphique basée sur la class Client
 *
 */

public class ClientGUI {
	
	public JFrame w;
	private int width = 900;
	private int height = 600;
	
	private Client client;

	public ClientGUI(Client client) {
		this.client = client;
		
		// Initialisation du JFrame
		w = new JFrame();
		w.setTitle("Liberama");
		w.setSize(width, height);
		w.setResizable(false);
		w.setLocationRelativeTo(null);
		w.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		
		JTabbedPane tabs = new JTabbedPane(JTabbedPane.LEFT);
		
		// Tab Personnel
		// Nom des colonnes
		String[] colPerso = {"No", "Poste", "Nom", "Prenom", "Sexe", "No Magasin"};
		JPanel perso = pan(colPerso, "personnel");
		tabs.addTab("Personnel", perso);
		
		// Tab Clients
		String[] colClients = {"No", "Nom", "Prenom", "Sexe", "Date", "Adresse", "Ville", "Code postal", "Préférences", "Identifiant"};
		JPanel clients = pan(colClients,"client");
		tabs.addTab("Clients", clients);
		
		// Tab Livre
		String[] colLivres = {"No", "nom_livre","nom_auteur","prenom_auteur","nom_editeur","type_livre","prix_livre","popularite"};
		JPanel livres = pan(colLivres,"livres");
		tabs.addTab("Livre", livres);

		// Tab Auteur
		String[] colAuteurs = {"No", "nom_auteur","prenom_auteur","sexe","natio_auteur"};
		JPanel auteurs = pan(colAuteurs,"auteur");
		tabs.addTab("Auteurs", auteurs);

		// Tab Editeurs
		String[] colEditeurs = {"No", "nom_editeur","adr_editeur"};
		JPanel editeurs = pan(colEditeurs,"editeur");
		tabs.addTab("Editeurs", editeurs);
		
		w.setContentPane(tabs);
		w.setVisible(true);
	}
	
	// Création du JPanel de chaque tab
	private JPanel pan(String[] col, String com) {
		JPanel p = new JPanel();
		// Boutons
		JButton ajouter = new JButton("Ajouter");
		JButton supprimer = new JButton("Supprimer");
		JButton modifier = new JButton("Modifier");
		JButton actualiser = new JButton("Actualiser");
		JButton quitter = new JButton("Quitter");
		// Ajout des boutons au JPanel
		p.add(ajouter);
		p.add(supprimer);
		p.add(modifier);
		p.add(actualiser);
		p.add(quitter);
				
		// Tableau des résultats
		// Commande pour récupérer la table correspondante à "com"
		String resp = client.command("get"+Constantes.SEP+com);
		// Séparation String par ;
		String[] parsed_resp = resp.split(Constantes.SEP, 0);
		int nbCols = col.length;
		int nbRows = parsed_resp.length / nbCols;
		String data[][] = new String[nbRows][nbCols];
		
		// Remplissage du tableau à deux dimensions
		for(int i = 0; i < nbRows; i++) {
			for(int j = 0; j < nbCols; j++) {
				data[i][j] = parsed_resp[1+j+nbCols*i];
			}
		}
		
		// Création du tableau graphique
		JTable table = new JTable(data, col);
		JScrollPane scroll = new JScrollPane(table);
		table.setFillsViewportHeight(true);
		scroll.setPreferredSize(new Dimension(780,500));
		p.add(scroll);
		
		// Listeners des boutons
		// Supprime la ligne sélectionnée
		supprimer.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				int sel = table.getSelectedRow();
				Object value = table.getValueAt(sel, 0);
				client.command("del"+Constantes.SEP+com+Constantes.SEP+value);
			}
		});
		
		// Réaffiche tout
		actualiser.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				new ClientGUI(client);
				w.dispose();
			}
		});
		
		// Ajoute un enregistrement dans une nouvelle fenêtre Ajout
		ajouter.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				new Ajout(com,client);
			}
		});
		
		// Modifie l'enregistrement sélectionné dans une nouvelle fenêtre Modif
		modifier.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				int sel = table.getSelectedRow();
				String value = (String)table.getValueAt(sel, 0);
				new Modif(com,client,value);
			}
		});
		
		// Termine la connexion avec le serveur et quitte l'application
		quitter.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				client.close();
				w.dispose();
			}
		});
		
		return p;
	}
}

// Classe permettant d'ajouter un enregistrement
class Ajout extends JFrame{
	private static final long serialVersionUID = -6893296782927629358L;

	public Ajout(String table, Client client) {
		super();
		setTitle("Ajouter");
		setResizable(false);
		setSize(400,200);
		setLocationRelativeTo(null);
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		JPanel p = new JPanel();
		
		int nbText;
		String[] nomCol = new String[]{};
		switch(table) {
			case "client":
				nbText = 10;
				nomCol = new String[]{"Nom","Prenom","Sexe","Date","Adresse","Ville","Code Postal","Préférences","Identifiant","Mot de passe"};
				break;
				
			case "auteur":
				nbText = 4;
				nomCol = new String[]{"Nom","Prenom","Sexe","Nationalité"};
				break;
				
			case "editeur":
				nbText = 2;
				nomCol = new String[]{"Nom","Adresse"};
				break;
				
			case "personnel":
				nbText = 5;
				nomCol = new String[]{"Poste","Nom","Prenom","Sexe","No Magasin"};
				break;
				
			case "livres":
				nbText = 6;
				nomCol = new String[]{"Type","Titre","Prix","No auteur","No editeur","Popularité"};
				break;
				
			default:
				nbText = 0;
				break;
		}
		
		// Initialise les champs de texte avec le label correspondant à la colonne
		for(int i=0; i<nbText; i++) {
			JTextField text = new JTextField();
			text.setPreferredSize(new Dimension(80, 30));
			JLabel label = new JLabel(nomCol[i]);
			label.setLabelFor(text);
			p.add(label);
			p.add(text);
		}
		
		// Envoie la commande
		JButton valider = new JButton("Valider");
		valider.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				String query = "add;"+table;
				for (Component component : p.getComponents()) {
		            if (component instanceof JTextField) {
		                query = query + Constantes.SEP + ((JTextField) component).getText();
		            }
		        }
				client.command(query);
				dispose();
			}
		});
		p.add(valider);
		
		setContentPane(p);
		setVisible(true);
	}
}

// Classe permettant de modifier un enregistrement
class Modif extends JFrame{
	private static final long serialVersionUID = -6893296782927629358L;

	public Modif(String table, Client client, String id) {
		super();
		setTitle("Modifier");
		setResizable(false);
		setSize(600,200);
		setLocationRelativeTo(null);
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		JPanel p = new JPanel();
		
		// Récupère le bon enregistrement pour afficher les valeurs
		String resp = client.command("getw"+Constantes.SEP+table+Constantes.SEP+id);
		String[] parsed_resp = resp.split(Constantes.SEP, 0);
		int nbText = parsed_resp.length/2;
		
		// Champs de texte remplis + label
		for(int i=0; i<nbText; i++) {
			JTextField text = new JTextField(parsed_resp[i+nbText]);
			text.setPreferredSize(new Dimension(80, 30));
			JLabel label = new JLabel(parsed_resp[i]);
			label.setLabelFor(text);
			p.add(label);
			p.add(text);
		}
		
		// Envoie la commande
		JButton valider = new JButton("Valider");
		valider.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				String query = "update;"+table;
				for (Component component : p.getComponents()) {
		            if (component instanceof JTextField) {
		                query = query + Constantes.SEP + ((JTextField) component).getText();
		            }
		        }
				client.command(query);
				dispose();
			}
		});
		p.add(valider);
		
		setContentPane(p);
		setVisible(true);
	}
}
