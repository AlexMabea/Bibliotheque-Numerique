package serveur;

import java.io.IOException;
import java.net.InetAddress;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.SocketException;
import java.rmi.UnknownHostException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.ListIterator;

import javax.swing.JTextArea;

/*
 * Serveur réseau utilisé par ServeurGUI
 */
public class Server {

	private ServerSocket server = null;
	private boolean isRunning = true;
	private JTextArea textArea = null;
	private SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy [HH:mm:ss]");
	
	// Liste qui stocke tous les clients connectés
	private List<Socket> clients = new ArrayList<Socket>();
	
	// Initialisation du serveur
	public Server(int port, int maxClient, String host, JTextArea textArea) {
		this.textArea = textArea;
		try {
			server = new ServerSocket(port, maxClient, InetAddress.getByName(host));
			debug("Serveur lancé. \n -Adresse: " + host + " \n -Port: " + port + " \n -Nombre de clients max: " + maxClient + "\n");
			open();
		} catch (UnknownHostException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
	// Lancement du serveur
	public void open() {
		Thread t = new Thread(new Runnable() {
			public void run() {
				while(isRunning == true) {
					try {
						// Attendre une connexion d'un client
						Socket client = null;
						try {
							client = server.accept();
							clients.add(client);
						}catch(SocketException e){
							System.err.println("Fermeture d'une socket.");
							break;
						}
						
						// Traitement du client dans un autre thread
						debug("Connexion avec le client " + client.getRemoteSocketAddress() + "\n");
						Thread t = new Thread(new ClientProcessor(client, textArea));
						t.start();
					} catch (IOException e) {
						e.printStackTrace();
					}
				}
			}
		});
		
		t.start();
	}
	
	// Fermeture du serveur et déconnexion de tous les clients encore connectés
	public void close() {
		isRunning = false;
		try {
			server.close();
			ListIterator<Socket> lt = clients.listIterator();
			// Déconnexion de tous les clients de la liste
			while(lt.hasNext()) {
				lt.next().close();
			}
			debug("Fermeture du serveur.\n");
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
	// Garde une trace pour les logs
	private void debug(String s) {
		if(textArea != null) {
			textArea.append(formatter.format(new Date()) + " " + s);
		}else {
			System.out.println(s);
		}
	}
}
