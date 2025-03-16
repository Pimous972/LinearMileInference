CREATE TABLE IF NOT EXISTS vehicules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS kilometrage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicule_id INT NOT NULL,
    date_releve DATE NOT NULL,
    kilometrage INT NOT NULL,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id)
);
