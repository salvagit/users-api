Users Api
==

Dependencies
==

    - LIB GD

Configure
==

  - $ composer install
  - $ cp .env.example .env
  - $ mkdir public/assets/
  - Create Users DB
  - Fill with data as appropriate in .env
  - mysql -u[DBUSER] -p [DBNAME] < users_structure.sql
