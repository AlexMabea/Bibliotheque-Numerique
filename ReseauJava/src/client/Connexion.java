package client;

import java.awt.Color;
import java.awt.EventQueue;
import java.awt.Font;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPasswordField;
import javax.swing.JTextField;

import autre.Constantes;

/**
 * Classe Main pour le client
 * Classe graphique permettant de se connecter et donc d'utiliser l'application
 *
 */

public class Connexion {

	// Variables graphiques
	private JFrame frmLiberamaLa;
	private JLabel lblNewLabel;
	private JLabel lblLaPlateformeDes;
	private JPasswordField passwordField;
	private JTextField idField;
	private JLabel lblError;
	
	private Client client = new Client();

	public static void main(String[] args) {

		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					Connexion window = new Connexion();
					window.frmLiberamaLa.setVisible(true);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}

	public Connexion() {
		initialize();
	}

	private void initialize() {
		// Initialisation JFrame
		frmLiberamaLa = new JFrame();
		frmLiberamaLa.setBackground(new Color(102, 153, 204));
		frmLiberamaLa.setForeground(new Color(102, 153, 204));
		frmLiberamaLa.setTitle("Liberama - La bibliothèque en ligne");
		frmLiberamaLa.setResizable(false);
		frmLiberamaLa.getContentPane().setBackground(new Color(102, 153, 204));
		frmLiberamaLa.setBounds(100, 100, 450, 300);
		frmLiberamaLa.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frmLiberamaLa.getContentPane().setLayout(null);

		// Titre
		lblNewLabel = new JLabel("Bienvenue chez Liberama !");
		lblNewLabel.setFont(new Font("Tahoma", Font.BOLD | Font.ITALIC, 20));
		lblNewLabel.setBounds(25, 21, 278, 38);
		frmLiberamaLa.getContentPane().add(lblNewLabel);

		lblLaPlateformeDes = new JLabel("La plateforme des lecteurs avisés !");
		lblLaPlateformeDes.setFont(new Font("Tahoma", Font.BOLD | Font.ITALIC, 16));
		lblLaPlateformeDes.setBounds(53, 40, 315, 38);
		frmLiberamaLa.getContentPane().add(lblLaPlateformeDes);
		
		// Champ "identifiant"
		idField = new JTextField();
		idField.setBounds(124, 134, 199, 20);
		frmLiberamaLa.getContentPane().add(idField);
		idField.setColumns(10);
		
		JLabel lblIdentifiant = new JLabel("Identifiant :");
		lblIdentifiant.setFont(new Font("Tahoma", Font.BOLD | Font.ITALIC, 12));
		lblIdentifiant.setBounds(25, 124, 104, 38);
		frmLiberamaLa.getContentPane().add(lblIdentifiant);
		
		// Champ "mot de passe"
		passwordField = new JPasswordField();
		passwordField.setBounds(124, 165, 199, 20);
		frmLiberamaLa.getContentPane().add(passwordField);
		
		JLabel lblMotDePasse = new JLabel("Mot de passe :");
		lblMotDePasse.setFont(new Font("Tahoma", Font.BOLD | Font.ITALIC, 12));
		lblMotDePasse.setBounds(25, 155, 104, 38);
		frmLiberamaLa.getContentPane().add(lblMotDePasse);
		
		// Bouton de connexion
		JButton btnConnect = new JButton("Connexion");
		btnConnect.addActionListener(new ActionListener() {
			// Action à effectuer
			public void actionPerformed(ActionEvent arg0) {
				String id = idField.getText();
				String myPass = String.valueOf(passwordField.getPassword());
				
				// Commande de connexion
				String rep = client.command("connect" + Constantes.SEP + id + Constantes.SEP + myPass);
				if(!rep.equals("")) {
					new ClientGUI(client);
					frmLiberamaLa.dispose();
				} else {
					lblError.setVisible(true);
				}
			}
		});
		btnConnect.setFont(new Font("Arial", Font.PLAIN, 14));
		btnConnect.setBounds(277, 225, 145, 23);
		frmLiberamaLa.getContentPane().add(btnConnect);
		
		// Bouton quitter
		JButton quit = new JButton("Quitter");
		quit.addActionListener(new ActionListener() {
			
			// Termine la connexion au serveur
			public void actionPerformed(ActionEvent arg0) {
				client.close();
				frmLiberamaLa.dispose();
			}
		});
		quit.setFont(new Font("Arial", Font.PLAIN, 14));
		quit.setBounds(25, 226, 145, 23);
		frmLiberamaLa.getContentPane().add(quit);
		
		// Message d'information
		JLabel lblSiVousAvez = new JLabel("Veuillez rentrer votre identifiant et mot de passe.");
		lblSiVousAvez.setFont(new Font("Tahoma", Font.ITALIC, 12));
		lblSiVousAvez.setBounds(25, 89, 315, 38);
		frmLiberamaLa.getContentPane().add(lblSiVousAvez);

		// Message d'erreur
		lblError = new JLabel("Votre identifiant et/ou votre mot de passe est incorrect");
		lblError.setForeground(new Color(204, 0, 0));
		lblError.setVisible(false);
		lblError.setBackground(new Color(255, 0, 0));
		lblError.setBounds(89, 185, 315, 14);
		frmLiberamaLa.getContentPane().add(lblError);
	}
}
