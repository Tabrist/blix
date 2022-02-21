Cześć,
Przesyłam udostępniam moją propozycję rozwiązania.

Środowisko na którym pracowałem:
Windows + WSL 2 (ubuntu 20) z zaintalowanym dockerem. Subdomena z jakiej korzystam 
to blix.loc skierowana na 127.0.0.1

Deploy jest przez docker-compose, gdzie tworzone jest:
- baza mysql
- phpmyadmin na porcie 8080
- nginx który przechwyca ruch i kieruje na odpowiedni kontenet
- nginx który serwuje pliki statyczne (web, tam czysty html i js go pobierania i wysyłania requestów do api)
- api, na symfony które przysporzyło okazało się bardziej czasochłonne niż zakładałem. Przeszukiwanie dokumentacji i innych źródeł w poszukiwania potencjalnie podstawowych funkcji (dla osób korzystających) chwilę zajęło. 
- consumer, również na symfony. Jako kolejkę użyłem domyślnego doctrina który z punktu logiki nie wnosi za wiele ale przerzucenie zapisu oraz aktualizacji na osobny proces pozwala na wymaganą asynchroniczność
 
Zbudowanie projektu na 6 letnim PC trwa około godziny

Treść zadania
Celem zadania jest przygotowanie prostej aplikacji typu blog. Blog powinien posiadać możliwość
dodawania wpisów za pomocą: API, CLI oraz formularza webowego; listowania wpisów za pomocą
paginowanego API wraz z informacją o ilości dodanych wszystkich wpisów oraz endpointa
do pobierania pojedynczego wpisu po identyfikatorze. Uwierzytelnianie nie jest wymagane.
Wpis powinien składać się z:
• tytułu (min 10 znaków, max 80 znaków)
• treści głównej (minimum 20 znaków)
• obrazka (jpg, max 1mb)
Pola powinny być walidowane oraz oczyszczone przed zapisem z wszelkich tagów HTML’owych
z wyjątkiem treści wpisu, dla której dozwolone jest użycie poniższych tagów HTML.
Dozwolone tagi HTML:
• ul
• li
• ol
• p
• strong
Uwagi dodatkowe:
• wygląd oraz kod HTML/JS nie będzie brany pod uwagę,
• kod powinien być wysokiej jakości,
• kod powinien być optymalny pod względem wydajności,
• przesadna inżyniera w tym przypadku jest mile widziana - wykaż się.
• Zadanie powinno być wykonane przy wykorzystaniu frameworka Symfony.
Dodatkowo mile widziane:
• kod dodawania wpisu przygotowany pod wykorzystanie go w sposób asynchroniczny,
• dokumentacja OpenApi/Swagger,
• dowolna forma deploymentu (Dockerfile, docker-compose, kubernetes albo dowolna inna
forma).

