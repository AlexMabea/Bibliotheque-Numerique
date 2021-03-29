package client;

import java.io.BufferedInputStream;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.Socket;
import java.net.UnknownHostException;

import autre.Constantes;

/*
 *  Classe client appelé par ClientGUI
 */

public class Client {
	private Socket connection = null;
	private PrintWriter writer = null;
	private BufferedInputStream reader = null;
	
	public Client() {
		try {
			// Connexion au serveur
			connection = new Socket(Constantes.SERVER_HOST, Constantes.SERVER_PORT);
			// Initialisation des méthodes de communication avec le serveur
			writer = new PrintWriter(connection.getOutputStream(), true);
			reader = new BufferedInputStream(connection.getInputStream());
		} catch(UnknownHostException e) {
			e.printStackTrace();
		} catch(IOException e){
			e.printStackTrace();
		}
	}
	
	// Fonction qui convertit le flux d'octets en provenance du serveur en String
	private String read() throws IOException{
		String response = "";
		int stream;
		byte[] b = new byte[4096];
		stream = reader.read(b);
		response = new String(b, 0, stream);
		
		return response;
	}
	
	// Envoie une commande de fermeture au serveur
	public void close() {
		command("close");
		writer.close();
	}
	
	// Fonction principale permettant l'envoie de commande au serveur les commandes sont composées d'un nom de commande et 
	// d'un string contenant les arguments le tout séparé par Constantes.SEP
	public String command(String command) {
		try {
			// Ecris la commande dans le writer
			writer.write(command);
			// Envoie le contenu du writer
			writer.flush();
			System.out.println("Client -> " + command);
			
			// Attente de la réponse
			String response = read();
			System.out.println("Serveur <- " + response);
			return response;
		} catch (IOException e) {
			e.printStackTrace();
		}
		return null;
	}
}