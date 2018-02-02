INSERT INTO Kayttaja (nimi, password, hallinto) VALUES ('OllimusPrime', '123', TRUE);
INSERT INTO Kayttaja (nimi, password, hallinto) VALUES ('Ollimus', '321', FALSE);

INSERT INTO Peli (nimi, kuvaus, julkaisija, julkaisupaiva) VALUES ('Hollow Knight', 'A metroidvania with heavy focus on graphics and atmospheric elements. Works well as a speedrun or as casual run.', 'Team Cherry', '2017-04-11');

INSERT INTO Arvostelu (kayttaja_id, peli_id, pisteet, sisältö, paivays) VALUES (1, 1, 9, 'Good game, the atmoshphere is nice, and the music is fitting, controls work well too.', '2018-01-02');
INSERT INTO Arvostelu (kayttaja_id, peli_id, pisteet, sisältö, paivays) VALUES (2, 1, 0, 'Hate this geim, is pooppers.', '2018-06-01');

INSERT INTO Peliehdotus (nimi, julkaisija) VALUES ('Shovel Knight', 'Yacht Club Games');
INSERT INTO Peliehdotus (nimi, julkaisija) VALUES ('Hovel Night', 'Yazzi Scrub Names');

INSERT INTO Peliehdotus_kayttaja (kayttaja_id, peliehdotus_id, paivays) VALUES (1, 2, '2018-01-01');
INSERT INTO Peliehdotus_kayttaja (kayttaja_id, peliehdotus_id, paivays) VALUES (1, 1, '2018-01-02');
INSERT INTO Peliehdotus_kayttaja (kayttaja_id, peliehdotus_id, paivays) VALUES (2, 1, '2017-12-12');