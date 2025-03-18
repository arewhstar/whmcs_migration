## Konfiguráció:

.env fájlban lehet a sémákat futtató adatbázisok paramétereit megadni

## Futtatás:

composer install

cd src

php ./migration_prepare.php

## Kérdések


Milyen IDE-t, tool-t használtál a megoldáshoz?
   - Visual Studio Code. Letöltöttem XAMPP-t, kompakt van benne php, lehet futtatni mysql szervert, phpmyadmin.

Honnan szerezted be az esetleges hiányzó infót?
 - Laravel Eloquent ORM doksi, chatgpt, deepseek

Körülbelül mennyi időre volt szükség a megoldáshoz?
     - 6 óra kb

Találkoztál olyan fogalommal, megoldással a feladat implementálása közben, ami eddigi karriered során nem jelent meg? Mi volt az?
    - Az Eloquent ORM kapcsán minden új volt, mert még sose használtam, viszont maga az általános fogalmakkal, eszközökkel már találkoztam.

Volt olyan rész, ami nehézséget okozott? Miért?
   - Összeségében nem volt egyértelmű meddig terjed a migráció előkészítése. Mennyire nyúljak bele a táblákba, így annyit készítettem elő, hogy szinkronba hoztam a migrálandó séma tábláinak azonosítóit, hogy ne ütközzön a migrálás során.
   - Próbáltam a elkapni az update trigger-t az id-k update-je során, de elv. elsődleges kulcsal nem lehet ilyet csinálni, így ennek a megoldása okozott nehézséget.
