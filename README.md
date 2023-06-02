# Contao Mimir Bundle

Mimir watches over your code and sends any exception he encounters directly to your Slack workspace.

## Installation

### Install using Contao Manager

Search for **slack** or **exception** and you will find this extension.

### Install using Composer

```bash
composer require plenta/contao-mimir
```

## Setup

1. Set up a Slack webhook (See [Slack API documentation](https://api.slack.com/messaging/webhooks)).
2. Install Mimir.
3. Configure Mimir

## Configuration

### Exception white- and blacklisting

There are some exceptions you commonly don't want to be notified about because they don't mean there's something wrong with your code (e.g. Contao's RedirectResponseExceptions). These exceptions are blacklisted by default. You can, however, whitelist them if you want to be notified about them.
You can also blacklist exceptions you don't want to be notified about.

### Configuration

You can easily configure Mimir through your `parameters.yml`:

```yaml
plenta_mimir:
  webhook: 'YourWebhookURL' # required
  channel: '#yourChannel' # optional
  exceptions: # optional
    whitelist:
      - 'Full/Exception/Class/Name'
    blacklist:
      - 'Full/Exception/Class/Name'
  debug: true # optional - Default false - Set to true if you want to receive messages in debug environment
```

## System requirements

- PHP: `^8.1`
- Contao: `^4.13 || ^5.1` (and later)