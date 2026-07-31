# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Calendar Versioning](https://calver.org/).

## [2026.0.0] - 2026-07-31
### New Features
- [`9567aea`](https://github.com/myerscode/templex/commit/9567aea4ae62abe8859d8c042f869dfe17861861) - **github**: created release workflow *(commit by [@oniice](https://github.com/oniice))*
- [`e4845b1`](https://github.com/myerscode/templex/commit/e4845b1e80cb7acc0e37827fea9a03fb9e6cfeb2) - **foreach**: add key => value iteration *(commit by [@oniice](https://github.com/oniice))*
- [`6783656`](https://github.com/myerscode/templex/commit/6783656d1a6ded4f7c18c4618eaa0c7ce3d95273) - **loops**: add loop metadata variables *(commit by [@oniice](https://github.com/oniice))*
- [`aec92e7`](https://github.com/myerscode/templex/commit/aec92e7274acd3daa9c2d608a23acdf58ec9548f) - **ternary**: add ternary and null coalescing *(commit by [@oniice](https://github.com/oniice))*
- [`98e36b3`](https://github.com/myerscode/templex/commit/98e36b3a3d4ce4dc0b374aaf4cc3a8f8c46486f1) - **conditions**: add elseif support *(commit by [@oniice](https://github.com/oniice))*
- [`7146bf9`](https://github.com/myerscode/templex/commit/7146bf9ac2e117010c18f2757a3ca2c0dc4275a6) - **control**: add logical && and || operators *(commit by [@oniice](https://github.com/oniice))*
- [`dc44f7f`](https://github.com/myerscode/templex/commit/dc44f7f6ecd9839b6820093f660c99d0e41da3c8) - **control**: add negation (!) operator *(commit by [@oniice](https://github.com/oniice))*

### Bug Fixes
- [`e8b9e57`](https://github.com/myerscode/templex/commit/e8b9e5732bba4175454d5620fc8e1801486479c5) - resolve PHPStan level 6 errors *(commit by [@oniice](https://github.com/oniice))*
- [`b0c481f`](https://github.com/myerscode/templex/commit/b0c481f0cc71c578f69de27d0afcee1711addf13) - resolve PHPStan level 8 errors *(commit by [@oniice](https://github.com/oniice))*

### Refactors
- [`13ee1c6`](https://github.com/myerscode/templex/commit/13ee1c610398182eb31b661fedb9d425c9c51b38) - apply Rector PHP 8.5 modernisation *(commit by [@oniice](https://github.com/oniice))*
- [`5a62a6c`](https://github.com/myerscode/templex/commit/5a62a6c3a16ab5a8fc0d59e29fc7d56583dad5f7) - remove dead code and unused test files *(commit by [@oniice](https://github.com/oniice))*
- [`3709531`](https://github.com/myerscode/templex/commit/3709531e4805ba885333394a35e1f1059198a1de) - apply PHP 8.5 modernisation pass *(commit by [@oniice](https://github.com/oniice))*
- [`86ea2bc`](https://github.com/myerscode/templex/commit/86ea2bce73e173a944952aa6b3dac64a680d09cd) - run Rector with base PHP config *(commit by [@oniice](https://github.com/oniice))*
- [`9f4ca31`](https://github.com/myerscode/templex/commit/9f4ca31e83b3fd5130a0b4a9b33cabc258a039b2) - **src**: modernise with rector *(commit by [@oniice](https://github.com/oniice))*

### Tests
- [`d437da0`](https://github.com/myerscode/templex/commit/d437da0425b6138aae77511af75c64498631b51c) - **coverage**: achieve 100% code coverage *(commit by [@oniice](https://github.com/oniice))*


## Unreleased

### Changed
- Bump minimum PHP version to 8.5
- Bump PHPUnit from ^9.5 to ^11.0
- Change package type from project to library
- Update GitHub Actions to latest versions

### Added
- Add Laravel Pint for code style enforcement
- Add PHPStan level 6 static analysis
- Achieve 100% test coverage

### Removed
- Remove squizlabs/php_codesniffer
- Remove symfony/var-dumper
- Remove dead code and unused test files

### Fixed
- Fix keyword typo in composer.json
- Fix issue tracker URL in README

## [2025.1.0](https://github.com/myerscode/templex/releases/tag/2025.1.0) - 2025-08-12

- [`aa1d235`](https://github.com/myerscode/templex/commit/aa1d235c5d845a6868bff4a0099f04bc8fd9e13d) feat(for): added the for control slot
- [`76c17e2`](https://github.com/myerscode/templex/commit/76c17e2b4eccf4809378c94ad77fabd2227946ca) feat(switch): added the switch control slot
[2026.0.0]: https://github.com/myerscode/templex/compare/2025.1.0...2026.0.0
