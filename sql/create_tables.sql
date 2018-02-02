CREATE TABLE Kayttaja(
id SERIAL UNIQUE PRIMARY KEY,
nimi varchar(30) NOT NULL,
password varchar(30) NOT NULL,
hallinto boolean NOT NULL
);

CREATE TABLE Peliehdotus(
id SERIAL PRIMARY KEY,
nimi varchar(180) NOT NULL,
julkaisija varchar(180) NOT NULL
);

CREATE TABLE Peliehdotus_Kayttaja(
id SERIAL PRIMARY KEY,
kayttaja_id INTEGER REFERENCES Kayttaja(id),
peliehdotus_id INTEGER REFERENCES Peliehdotus(id),
paivays DATE NOT NULL
);

CREATE TABLE Peli(
id SERIAL PRIMARY KEY,
nimi varchar(180) NOT NULL,
kuvaus TEXT NOT NULL,
julkaisija varchar(180) NOT NULL,
julkaisupaiva DATE NOT NULL
);

CREATE TABLE Arvostelu(
id SERIAL PRIMARY KEY,
kayttaja_id INTEGER REFERENCES Kayttaja(id),
peli_id INTEGER REFERENCES Peli(id),
pisteet INTEGER NOT NULL,
sisältö varchar(5000),
paivays DATE NOT NULL
);