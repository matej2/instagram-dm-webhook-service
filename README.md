# instagram-dm-webhook-service

> This will not be autorespond / autosend service. Similar to Facebook chat bot service, this service will use webhooks to communicate with external services to get response based on received message.


*This is a draft for instagram dm webhook service.*

This service will not use official api as it is not possible to collect messages data and to reply to incoming messages. Instead it will use unofficial api / library (for ex: https://github.com/LevPasha/Instagram-API-python).


## Contributing
You can submit your PRs on `beta` branch. Use beta branch for testing (after the changes are merged).

### Instructions

1. Clone to local directory
2. Cd into dir
3. Copy and rename `config.example.json`
4. Write webhook data 
2. `php -f main.php`


## Webhook service

Service  will use webhooks to communicate with third-party services. In this case, serice would periodically check for new messages (every 15 min or more) and would send webhook as specified in settings. Here we can support only text-based communication between IG DM and third-party service.

### How will it work
We will configure this service to run every 15 minutes. It will check latest messages from every sender. If it will match and of the keywords it will send webhook as configured. It wil also track each users message count, check time and webhook limit so that the service will not exceed any limitations.