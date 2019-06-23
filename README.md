# instagram-dm-webhook-service

> This will not be autorespond / autosend service. Similar to Facebook chat bot service, this service will use webhooks to communicate with external services to get response based on received message.


This is Instagram direct message webhook service. It will read messages from Instagram and send webhooks to chatbot services. It will then reply to message in Instagram dm. Due to Instagram limitations, we can support only text-based communication between IG DM and third-party service.

This service will not use official api as it is not possible to collect messages data and to reply to incoming messages. Instead it will use unofficial api / library


## Contributing
You can submit your commits on `beta` branch. For larger changes (features) we suggest you make separate branch and then make PR to `beta` branch.

### Environment setup
For instagram api to work, you need to [enable php extensions](https://github.com/mgp25/Instagram-API/wiki/Dependencies). You also need to add [certificate and its configuration](https://stackoverflow.com/questions/24611640/curl-60-ssl-certificate-unable-to-get-local-issuer-certificate) in `php.ini`

### Instructions

1. Clone to local directory
2. Cd into dir
3. Copy and rename `config.example.json` to `config.json`
4. Fill in your configuration (you can use this for testing: https://webhook.site)
5. `php -f main.php`




## How will it work
We will configure this service to run every 15 minutes. It will check latest messages from every sender. If it will match any of the keywords it will send webhooks as configured.

## Planned features

* Per month / per hour limit checks

Before calling webhook, service will count already sent webhooks to check (if defined) hourly and monthly quota limits. 

* Wait list

Furthermore, if limit quotas have been reached, the messgae is sent to wating list in database. Message will be send when quotas are reset.

* Keyword blacklist

To prevent replying to auto-send bots, service will use keyword checks. If word is blacklisted, service will not send webhooks but it will log event.

* After send method

After calling webhook, service will call another method enabling you to quickly add other actions in service.
