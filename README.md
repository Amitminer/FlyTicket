# FlyTicket

FlyTicket is a plugin for PocketMine-MP that allows players to fly for a limited amount of time using a fly ticket.

## Features

- Players can use the `/flyticket` command to access the fly ticket UI.
- The duration of the fly ticket can be configured.
- Players can purchase a fly ticket with an in-game currency.
- The UI is created using FormAPI.
- Players will have permission to fly until the fly ticket expires.

## Usage

To use this plugin, simply add it to your PocketMine-MP server's `plugins` folder. The default configuration will allow players to purchase a fly ticket for 60 minutes.

Use the `/flyticket` command to open the UI and purchase a fly ticket. Players will need to have enough in-game currency to purchase a ticket.

## Configuration

You can customize the plugin by editing the `config.yml` file located in the `plugins/FlyTicket` folder. Here are the available configuration options:

- `Price`: The cost of the fly ticket in the in-game currency.
- `Min`: The minimum duration of the fly ticket in minutes.
- `Max`: The maximum duration of the fly ticket in minutes.
- `ItemName`: The name of the fly ticket item in the player's inventory.
- `FormTitle`: The title of the UI form.
- `content`: The content of the UI form.

## Permissions *TODO

- `flyticket.use`: Allows the player to use the `/flyticket` command and purchase a fly ticket.

# TODO LIST
- [X] UPDATING TO PM5
- [ ] USE RankSystem api
- [X] SUPPORT CUSTOM ITEMS AS FLYTICKET.
- [ ] use timer api to manage time!
- [X] CREATE MessageManager
- [X] Use Database
## Support

If you need help with this plugin or want to report a bug, please create a new issue on the GitHub repository.

## License

This plugin is licensed under the MIT License. See the `LICENSE` file for more information.
