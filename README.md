# domain driven design

## Overview
This an example of domain-driven design implementation of API server (use case a marketplace).

## Project Structure

```bash
├── app
│   ├── config
│   ├── library
│   └── modules (this is where the domain driven design is)
│       ├── account
│       ├── chat
│       ├── marketplace-customer
│       ├── marketplace-toko
│       ├── notification
│       ├── wallet
│       ├── env.example
│       ├── Application.php
│       └── Console.php
├── docker (contain docker files and configs)
├── public
│   ├── .htaccess
│   └── index.php
├── storage
├── .gitignore
├── .htaccess
├── cli.php
├── composer.json
├── composer.lock
├── phpunit.xml
└── README.md
```