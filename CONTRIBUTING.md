# Contributing

## Issue reporting

Please raise all issues in the [GitHub issue tracker](https://github.com/dhensby/silverstripe-masquerade/issues). Where
possible, please provide versions of relevant dependencies you have installed and steps to replicate.

## Contributing code

All code contributions must be made via the [GitHub pull request system](https://github.com/dhensby/silverstripe-masquerade/pulls).
This project follows [SemVer](http://semver.org), so please open pull requests against appropriate branches for your changes.

All contributors retain copyright and attribution of their works but agree to make their work available under the same licence of this
project, which may change from time-to-time.

## Development

After cloning the repository, install dependencies:

```sh
composer install
```

### Running tests

```sh
composer run-script test
```

### Linting

```sh
composer run-script lint
```

To automatically fix linting issues:

```sh
composer run-script lint:fix
```
