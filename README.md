# Instalace

## Závislosti

- Linux (funguje i na Windows s WSL)
- Pro běh je potřeba mít nainstalovaný **Docker** (instalace v návodu níže)
- Pro stáhnutí projektu ideální mít nainstalovaný **GIT** (instalace v návodu níže), není to podmínkou, ale návod s tím počítá 

## Instalace dockeru

Nainstalovat docker podle návodu (sekce Install using the apt repository)
https://docs.docker.com/engine/install/ubuntu/

## Instalace GIT

Nainstalovat příkazem v terminálu
```
sudo apt update
sudo apt install git
```

## Instalace monitoring aplikace

Stáhnutí aplikace příkazem v terminálu

```
git clone https://github.com/krencl/monitoring-izscr.git
cd monitoring-izscr
chmod +x *.sh
```

# Spuštění aplikace

Aplikace se spouští pomocí `./start.sh` v adresáři aplikace.

*První spuštění chvíli potrvá (v závislosti na rychlosti internetu), protože se musí stáhnout všechny potřebné součásti.*

Aplikace pak bude běžet v prohlížeči na adrese:
http://localhost:8080

Pokud byla aplikace už jednou spuštněna a docker je nastaven po spuštění systému, tak se automaticky zapne s přihlášením uživatele.

Na plochu Ubuntu lze přidat zástupce jednorázovým spuštěním skriptu `./create-shortcut.sh`.

Případné vypnutí je pomocí  `./stop.sh`.

# Konfigurace aplikace

Prvotní konfigurace probíha při spuštění aplikace pomocí `./start.sh`;

Konfigurace je uložená v souboru `app/config.json`.

Pokud bude potřeba změnit konfiguraci v budoucnu, např. přihlašovací údaje k emailu, tak upravit lze editací tohoto souboru.

# Aktualizace

V příkazové řádce v adresáři s aplikací spustit `./update.sh`

Pozor! Nesmí být upraveny originální zdrojové soubory, jinak se aktualizace nepovede.
