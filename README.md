AutoOferty
===========

09.10.2018

Wymagania:
---------------

 * Docker
 * Docker Compose
 
Uruchomienie:
---------------

 1. `docker-compose up -d` w katalogu głównym pojektu
 2. Uruchomienie `docker exec -it auto-oferty_webserver composer install`
 3. Podanie danych dostępowych do bazy daych
 
 Uwaga: Przed uruchomieniem należy zatrzymać wszystkie usługi działające na potrach 80!
 
Baza danych:
---------------

  Aplikacja wymaga zewnętrznej bazy danych MySQL.
  Dane połączenia z bazą należy uzupełnić podczas wykonywania polecenia `docker exec -it auto-oferty_webserver composer install`
  lub w pliku app/config/parameters.yml
  
  Po zainstalowaniu wszystkich zależności i skonfigurowaniu danych należy uruchomić komendę:
  `docker exec -it auto-oferty_webserver php bin/console doctrine:schema:update --force`
  
 
