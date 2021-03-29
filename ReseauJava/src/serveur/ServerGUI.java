package serveur;

import java.awt.Dimension;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.JTextField;

import autre.Constantes;

/**
 * Classe graphique permettant de lancer et arrêter le serveur
 *
 */

public class ServerGUI {
	
	Server server = null;
	
	public static void main(String[] args) {
		new ServerGUI();
	}
	
	public ServerGUI() {
		// Initialisation du JFrame
		JFrame w = new JFrame();
		w.setTitle("Serveur");
		w.setSize(500, 450);
		w.setResizable(false);
		w.setLocationRelativeTo(null);
		w.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		
		// Initialisation du JPanel
		JPanel p = new JPanel();
		
		// Text fields
		JTextField host = new JTextField(Constantes.SERVER_HOST);
		host.setPreferredSize(new Dimension(100, 30));
		JLabel hostLabel = new JLabel("Host");
		hostLabel.setLabelFor(host);
		p.add(hostLabel);
		p.add(host);
		
		JTextField port = new JTextField(String.valueOf(Constantes.SERVER_PORT));
		port.setPreferredSize(new Dimension(100, 30));
		JLabel portLabel = new JLabel("Port");
		portLabel.setLabelFor(port);
		p.add(portLabel);
		p.add(port);
		
		JTextField maxClient = new JTextField(String.valueOf(Constantes.SERVER_MAX_CLIENT));
		maxClient.setPreferredSize(new Dimension(100, 30));
		JLabel clientLabel = new JLabel("Clients max");
		clientLabel.setLabelFor(maxClient);
		p.add(clientLabel);
		p.add(maxClient);
		
		// Text area qui affiche tout le traffic d'infos. Sauvegarde cette valeur dans un fichier log
		JTextArea textArea = new JTextArea("", 23, 40);
		JScrollPane scrollPane = new JScrollPane(textArea);
		textArea.setEditable(false);
		p.add(scrollPane);
		
		// Bouton start et stop
		JButton start = new JButton("Start");
		JButton stop = new JButton("Stop");
		
		// Lance le serveur
		start.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				server = new Server(Integer.parseInt(port.getText()), Integer.parseInt(maxClient.getText()), host.getText(), textArea);
				start.setEnabled(false);
				stop.setEnabled(true);
			}
		});
		p.add(start);
		
		// Stop le serveur
		stop.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				server.close();
				server = null;
				start.setEnabled(true);
				stop.setEnabled(false);
				
				// Ecriture dans un fichier de log
				try {
					BufferedWriter writer = new BufferedWriter(new FileWriter(Constantes.LOGS, true));
					writer.append("\n" + textArea.getText());
					writer.close();
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
		});
		stop.setEnabled(false);
		p.add(stop);
		
		w.setContentPane(p);
		w.setVisible(true);
	}

}
