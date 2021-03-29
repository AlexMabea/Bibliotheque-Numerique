-- Les séquences permettent l'incrémentation automatique des numéros de clé primaires (nombrechoisi+x)
CREATE SEQUENCE auteur_sequence
start 1
increment 1;

CREATE SEQUENCE editeur_sequence
start 1
increment 1;

CREATE SEQUENCE client_sequence
start 1
increment 1;

CREATE SEQUENCE livres_sequence
start 1
increment 1;

CREATE SEQUENCE stock_sequence
start 1
increment 1;

CREATE SEQUENCE employe_sequence
start 1
increment 1;

---------------------------------------------------------------------------------------------------------------------------
-- 9 TABLES / 49 attributs

CREATE TABLE public.auteur (
    no_auteur character(8) NOT NULL,
    nom_auteur character varying(30),
    prenom_auteur character varying(30),
    sexe_auteur character(5),
    natio_auteur character varying(30),
    CONSTRAINT auteur_pkey PRIMARY KEY (no_auteur)
);

CREATE TABLE public.editeur (
    no_editeur character(8) NOT NULL,
    nom_editeur character varying(50),
    adr_editeur character varying(150),
    CONSTRAINT no_editeur PRIMARY KEY (no_editeur)
);

CREATE TABLE public.livres (
    no_livre character(8) NOT NULL,
    type_livre character varying(30),
    nom_livre character varying(100),
    prix_livre float,
    auteur character(8),
    editeur character(8),
    popularite smallint,
    image bytea,

    CONSTRAINT livres_pkey PRIMARY KEY (no_livre),
    CONSTRAINT livres_fk1 FOREIGN KEY (auteur) REFERENCES auteur(no_auteur),
    CONSTRAINT livres_fk2 FOREIGN KEY (editeur) REFERENCES editeur(no_editeur)
);

CREATE TABLE public.client (
    no_client character(8) NOT NULL,
    nom_client character varying(50),
    prenom_client character varying(50),
    sexe_client character(5),
    date_client DATE,
    adr_client character varying(200),
    ville_client character varying(100),
    cp_client character(5),
    pref_client character varying(20),
    identifiant character varying(50),
    mdp character varying (200),

    CONSTRAINT client_pkey PRIMARY KEY (no_client)
);


CREATE TABLE public.commande (
    no_commande character(8) NOT NULL,
    totalLivres character varying(200),
    ad_livraison character varying(100),
    nom_livraison character varying(30),
    prenom_livraison character varying(30),
    num_tel character(10),
    no_client character(8),
    prixTotal float,

    CONSTRAINT commande_pkey PRIMARY KEY (no_commande),
    CONSTRAINT commande_fk1 FOREIGN KEY (no_client) REFERENCES client(no_client)
);

CREATE TABLE public.stock (
    no_stock character(8) NOT NULL,
    no_livre character(8),
    dispo boolean,
    nb_stock smallint,
    no_magasin character(8),

    CONSTRAINT stock_pkey PRIMARY KEY (no_stock),
    CONSTRAINT stock_fk1 FOREIGN KEY (no_livre) REFERENCES livres(no_livre)
);

CREATE TABLE public.magasin (
    no_magasin character(8) NOT NULL,
    adr_magasin character varying(60),
    tel_magasin character(10),

    CONSTRAINT magasin_pkey PRIMARY KEY (no_magasin)
);

CREATE TABLE public.employe_liberama (
    no_employe character(8) NOT NULL,
    poste character varying(50),
    nom_employe character varying(30),
    prenom_employe character varying(30),
    sexe_employe character(5),
    no_magasin character(8),

    CONSTRAINT employe_liberama_pkey PRIMARY KEY (no_employe)

);

CREATE TABLE public.employe_Admin(
    identifiant_employe character varying(50),
    mdp_employe character varying(100)
)INHERITS (employe_liberama);

ALTER TABLE ONLY public.employe_liberama ADD CONSTRAINT employe_liberama_fk1 FOREIGN KEY (no_magasin) REFERENCES magasin(no_magasin);
ALTER TABLE ONLY public.stock ADD CONSTRAINT stock_fk2 FOREIGN KEY (no_magasin) REFERENCES magasin(no_magasin);

GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO projet;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO projet;
