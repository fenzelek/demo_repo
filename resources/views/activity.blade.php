<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:hover {
            background-color: #f9f9f9;
        }

        /* Dodaj styl dla animowanego elementu */
        .loader {
            position: fixed;
            top: 50%;
            left: 50%;

            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Activity List</h1>
    <!-- Formularz filtrowania -->
    <form id="filter-form">
        <label for="type">Type:</label>
        <select id="actionType" name="actionType">
            <option value="">All</option>
            <option value="FLT">FLT</option>
            <option value="SBY">SBY</option>
            <!-- Dodaj więcej opcji jeśli jest taka potrzeba -->
        </select>

        <label for="week">Wybierz tydzień:</label>
        <select id="week" name="week">

            <!-- Dodaj inne opcje, jeśli są potrzebne -->
        </select>

        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate">

        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" name="endDate">

        <label for="locationFrom">Location From:</label>
        <input type="text" id="locationFrom" name="locationFrom">

        <label for="locationTo">Location To:</label>
        <input type="text" id="locationTo" name="locationTo">

        <button type="submit">Filter</button>
    </form>


    <!-- Dodaj pusty tabelę -->
    <table id="activity-table">
        <thead>
        <tr>
            <th>Type</th>
            <th>From</th>
            <th>To</th>
            <th>Start</th>
            <th>End</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <!-- Dodaj animowany element loader -->
    <div class="loader" id="loader" style="display: none"></div>
</div>

<!-- Dodaj skrypt JavaScript do pobierania wszystkich rekordów i wyświetlania ich w tabeli -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Utwórz nowy obiekt Date
        var currentDate = new Date('2022-01-01');
        var weekSelect = document.getElementById("week");

        // Iteruj przez tygodnie
        for (var i = 0; i < 52; i++) {
            var option = document.createElement("option");
            option.text = "Tydzień " + (i + 1);
            option.value = i + 1;
            weekSelect.add(option);
            // Przesuń datę o 7 dni
            currentDate.setDate(currentDate.getDate() + 7);
        }

        // Pobierz elementy formularza
        var startDateInput = document.getElementById("startDate");
        var endDateInput = document.getElementById("endDate");
        var weekSelect = document.getElementById("week");

        // Funkcja do ustawiania daty początkowej i końcowej na podstawie wybranego tygodnia
        function setWeekDates() {
            // Ustal wartość wybranego tygodnia
            var weekNumber = parseInt(weekSelect.value);
            // Oblicz datę początkową i końcową na podstawie numeru tygodnia
            var startDate = new Date('2022-01-01');
            startDate.setDate(startDate.getDate() + (weekNumber - 1) * 7); // Data początkowa
            var endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + 6); // Data końcowa
            console.log("data startowa: " + startDate);
            console.log("data koncowa: " + endDate);
            // Ustaw wartości dat w polach formularza
            startDateInput.value = startDate.toISOString().split('T')[0];
            endDateInput.value = endDate.toISOString().split('T')[0];
        }

        // Ustaw daty na podstawie wybranego tygodnia przy załadowaniu strony
        setWeekDates();

        // Obsługa zmiany wybranego tygodnia
        weekSelect.addEventListener("change", setWeekDates);
    });
    // Funkcja do ładowania danych przy starcie strony
    window.addEventListener("load", function() {
        // Wywołaj funkcję do pobrania danych
        fetchData();
    });

    // Funkcja do pokazywania kółka ładowania
    function showLoader() {
        document.getElementById("loader").style.display = "block";
    }

    // Funkcja do chowania kółka ładowania
    function hideLoader() {
        document.getElementById("loader").style.display = "none";
    }

    // Nasłuchuj na zdarzenie submit formularza
    document.getElementById("filter-form").addEventListener("submit", function(event) {
        // Zatrzymaj domyślne działanie formularza
        event.preventDefault();
        // Wywołaj funkcję do pobrania danych
        fetchData();
    });

    // Funkcja do pobierania danych z serwera
    function fetchData() {
        // Pokaż kółko ładowania
        showLoader();

        // Utwórz nowy obiekt XMLHttpRequest
        var xhttp = new XMLHttpRequest();
        // Określ, co zrobić po otrzymaniu odpowiedzi
        xhttp.onreadystatechange = function() {
            // Sprawdź, czy odpowiedź jest gotowa i czy jest sukcesem
            if (this.readyState == 4 && this.status == 200) {
                hideLoader();

                // Parsuj odpowiedź jako JSON
                var response = JSON.parse(this.responseText);

                // Sprawdź, czy istnieje tablica data w odpowiedzi
                if (response.hasOwnProperty('data')) {
                    // Pobierz dane z tablicy data
                    var activities = response.data;

                    // Wyświetl pobrane dane w tabeli
                    var table = document.getElementById("activity-table").getElementsByTagName('tbody')[0];
                    // Wyczyść tabelę przed dodaniem nowych danych
                    table.innerHTML = '';
                    // Iteruj przez wszystkie pobrane aktywności
                    activities.forEach(function(activity) {
                        var row = table.insertRow();
                        var keys = Object.keys(activity);
                        // Wstaw kolejne komórki do wiersza
                        keys.forEach(function(key) {
                            var cell = row.insertCell();
                            cell.appendChild(document.createTextNode(activity[key]));
                        });
                    });
                } else {
                    console.error('Brak danych w odpowiedzi JSON.');
                }
            }
        };
        // Pobierz wartości z formularza
        var formData = new FormData(document.getElementById("filter-form"));
        // Utwórz obiekt URLSearchParams do przechowywania parametrów
        var params = new URLSearchParams();
        // Dodaj tylko te parametry, które mają wartość
        formData.forEach(function(value, key) {
            if (value !== '') {
                params.append(key, value);
            }
        });
        // Utwórz pełny URL z parametrami tylko dla tych, które mają wartość
        var url = "/api/activities";
        if (params.toString() !== '') {
            url += "?" + params.toString();
        }
        // Wyślij zapytanie GET do pobrania danych z uwzględnieniem filtrów
        xhttp.open("GET", url, true);
        xhttp.send();
    }
</script>

</body>
</html>
