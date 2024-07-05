
<h1 align="center">
  <br>
  Fieldlab
  <br>
</h1>

<h4 align="center">Een fieldlab applicatie om opdrachten te beheren.</h4>

<p align="center">
  <a href="#belangrijkste-kenmerken">Belangrijkste kenmerken</a> •
  <a href="#hoe-te-gebruiken">Hoe te gebruiken</a> •
  <a href="#credits">Credits</a>
</p>

![image](https://github.com/horizoncollege/Fieldlab/assets/157012382/7f2934ff-2ce1-40ec-b005-7fe0e3e1dc94)

## Belangrijkste kenmerken

* Opdrachten beheren
  - Opdrachten aanmaken als docent
  - Opdrachten wijzigen als docent
  - Opdrachten verwijderen als docent
  - Opdrachten bekijken en openen
* Wachtwoord veranderen 
* Studenten beheren via de docenten panel
  - Studenten registreren
  - Klas wijzigen van studenten
* In- uitschrijven bij opdrachten

## Hoe te gebruiken

Je hebt nodig:
- PHP 8.3.6
- Mysql 8.0.37

```bash
# Clone de repository
$ git clone https://github.com/horizoncollege/Fieldlab
# Import de sql database
$ mysql -u root -p fieldlab < import.sql
# Run de applicatie
$ php -S localhost:80
```

## Functie verzoek

- Dark mode
- In- uitschrijven weg halen voor docenten
- Zien of een gebruiker een docent of student is bij de admin panel
- Text-field groter voor opdracht toevoegen

## Credits

- [Vaia](https://github.com/Vaia05)
- [Kars](https://github.com/lean-cc)
- [Lennard](https://github.com/kaasbaas08)
