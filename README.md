# lodestone-parser
Parse lodestone for those juicy details

- Run `php cli <func> <arguments>` for debugging
- Run `php tests` to validate tests

| CLI Command     |Arguments|Description|
|-----------------|-|-|
| `character`     |`<id>`|Prints a character parse.
| `characterfull` |`<id>`|Prints a character parse with its complete class jobs list.
| `freecompany`   |`<id>`|Prints a freecompanies parse.|
| `pvpteam`       |`<id>`|Prints a pvpteam parse.|
| `linkshell`     |`<id>`|Prints a linkshell parse.|
| `achievements`  |`<id>`|Prints a characters achievement parse.|
| `banners`       |none|Prints the currently displayed banners on the lodestone homepage.|
| `leaderboards`  |`feast`,`potd`,`hoh`|Prints the current leaderboard parse for The Feast, Palace of The Dead, or Heaven on High.|

All commands accept a flag to print the returned blob to a json file (with `-f filename` after the `<func>`).

The json file is saved in the `export` directory under the directory with the command name.

## Languages supported
- `en`: English (default)
- `fr`: French
- `de`: German
- `ja`: Japanese

## Commands with language support
- `character`
- `characterfull`
- `freecompany`
- `achievements`

## Examples
```
// prints returned object to file myCharacter.json
php cli character <lodestoneid> -f myCharacter
```
```
// prints returned object to file myCharacter.json from the French Lodestone
php cli character <lodestoneid> -f myCharacter -l fr
```
