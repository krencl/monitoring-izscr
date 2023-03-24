# Instalace

## Závislosti

- Pro běh je potřeba mít nainstalovaný **Docker** (instalace v návodu níže)
- Pro stáhnutí projektu ideální mít nainstalovaný **GIT** (instalace v návodu níže), není to podmínkou, ale návod s tím počítá 

## Instalace dockeru

Stáhnout DEB package z oficiálních stránek:
https://docs.docker.com/desktop/install/ubuntu/

A nainstalovat příkazem v terminálu
```
sudo apt update
sudo apt install <cesta ke staženému souboru>
```


Povolení spuštění po startu systému
```
sudo systemctl enable docker
```

## Instalace GIT

Nainstalovat příkazem v terminálu
```
sudo apt update
sudo apt install git
```

## Instalace monitoring aplikace

Stáhnutí aplikace příkazem v terminálu

```
git clone XXX
cd XXX
chmod +x *.sh
```

# Spuštění aplikace

Aplikace se spouští pomocí `start.sh` v adresáři aplikace. Případné vypnutí je pomocí  `stop.sh`.

Aplikace pak bude běžet v prohlížeči na adrese:
http://localhost:8080

Pokud byla aplikace už jednou spuštněna a docker je nastaven po spuštění systému, tak se automaticky zapne s přihlášením uživatele.

Na plochu Ubuntu lze přidat zástupce jednorázovým spuštěním skriptu `create-shortcut.sh`.

# Konfigurace aplikace

Prvotní konfigurace probíha při spuštění aplikace pomocí `start.sh`;

Konfigurace je uložená v souboru `app/config.json`.

Pokud bude potřeba změnit konfiguraci v budoucnu, např. přihlašovací údaje k emailu, tak upravit lze editací tohoto souboru.
