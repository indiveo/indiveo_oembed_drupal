# Indiveo oEmbed Module

Deze Drupal module maakt het mogelijk om Indiveo video's eenvoudig in te sluiten via oEmbed functionaliteit. Je kunt gewoon een Indiveo URL plakken in de teksteditor en de video wordt automatisch ingesloten.

## Beschrijving

De Indiveo oEmbed module:
- Voegt Indiveo toe als oEmbed provider aan Drupal
- Configureert automatisch de teksteditor (CKEditor) om media-items te ondersteunen
- Maakt het mogelijk om Indiveo video's in te sluiten door simpelweg de URL te plakken
- Werkt naadloos samen met Drupal's media systeem

## Vereisten

- Drupal 10 of 11
- De volgende Drupal core modules:
  - [Media](https://www.drupal.org/docs/8/core/modules/media/overview)
  - [Media Library](https://www.drupal.org/project/media_library)
  - [oEmbed Providers](https://www.drupal.org/project/oembed_providers)

## Installatie

1. **Installeer de module via Composer:**
```bash
composer require indiveo/indiveo_oembed_drupal
```
De module wordt automatisch geplaatst in `modules/contrib/indiveo_oembed_drupal`.
2. **Schakel de module in via Drush:**
```bash
drush en indiveo_oembed_drupal
```
3. **Of schakel de module in via de Drupal UI:**
   - Ga naar Extend (`/admin/modules`)
   - Zoek naar "Indiveo oEmbed"
   - Vink de module aan en klik op "Install"

## Gebruik

### Video's insluiten in content

1. **In de teksteditor:**
   - Plaats je cursor waar je de video wilt invoegen
   - Plak de volledige Indiveo URL op een nieuwe regel
   - De URL wordt automatisch omgezet naar een ingesloten video

2. **Via het media systeem:**
   - Gebruik de media knop in de teksteditor
   - Voeg een nieuwe media-item toe met de Indiveo URL
   - Selecteer het media-item om het in je content in te voegen

### Ondersteunde URL formaten

De module herkent Indiveo URL's automatisch. Voorbeelden van ondersteunde formaten:
- Directe Indiveo video URL's
- Ingesloten (embed) URL's

## Wat doet de module technisch?

### Bij installatie:
- **Teksteditor configuratie:** Voegt de media knop toe aan de basic_html editor toolbar
- **Filter configuratie:** 
  - Voegt `<drupal-media>` tags toe aan toegestane HTML
  - Activeert de media embed filter
  - Voegt media module dependency toe
- **oEmbed provider:** Registreert Indiveo als oEmbed provider via configuratie

### Bij deïnstallatie:
- Draait alle configuratiewijzigingen terug
- Verwijdert alle toegevoegde configuratie
- Laat geen sporen achter in je Drupal installatie

## Configuratie

Na installatie kun je de module configureren via:
- **Media types:** `/admin/structure/media` - Beheer media types
- **Text formats:** `/admin/config/content/formats` - Tekst format instellingen
- **oEmbed providers:** `/admin/config/media/oembed-providers` - Beheer oEmbed providers

## Probleemoplossing

### Video wordt niet weergegeven
- Controleer of de Indiveo URL correct is
- Zorg ervoor dat de media embed filter is geactiveerd
- Controleer of `<drupal-media>` tags zijn toegestaan in je text format

### Editor knop ontbreekt
- Ga naar `/admin/config/content/formats`
- Bewerk het "Basic HTML" format
- Controleer of de "Insert Media" knop is toegevoegd aan de toolbar

### Cache problemen
- Leeg je Drupal cache na installatie:
```bash
drush cr
```

## Technische details

### Bestanden
- `indiveo_oembed_drupal.info.yml` - Module definitie en dependencies
- `indiveo_oembed_drupal.install` - Installatie en deïnstallatie hooks
- `config/install/` - oEmbed provider configuratie (aangemaakt door module)

### Dependencies
De module is afhankelijk van:
- `drupal:media` - Core media functionaliteit
- `drupal:media_library` - Media bibliotheek UI
- `drupal:oembed_providers` - oEmbed provider systeem

## Licentie

Deze module volgt de Drupal licentie voorwaarden.

## Ondersteuning

Voor vragen en ondersteuning:
- Controleer de Drupal logs voor foutmeldingen
- Zorg ervoor dat alle vereiste modules zijn geïnstalleerd
- Test met verschillende Indiveo URL formaten