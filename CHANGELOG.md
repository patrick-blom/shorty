## [0.3.1] - 2022-11-25
### Added
- GitHub actions pipeline for php 8.0 & 8.1
### Changed
- PHPStan lvl from 3 to 5
- Edit PHPUnit config to prevent deprecation warnings
### Removed
- Removed Travis build
- Removed fixed platform requirement
- Deleted unused docker files for doctrine

## [0.3.0] - 2022-11-03
### Changed
- Update symfony from 4.4 to 5.4 LTS incl. dependencies
- Update for php 8.1
- Add test credentials to .env.test
- Prevent migrations from autoloading
### Removed
- Removed support for PHP 7.2
- Removed support for PHP 7.3
- Removed support for PHP 7.4

## [0.2.0] - 2020-03-26
### Added
- Add new method in UriController to fetch Response status texts
### Changed
- Update symfony from 4.3 to 4.4 incl. dependencies
- Fix phpcs hints for undefined variables
- Edit doctrine config to prevent deprecation warnings
- Edit the README.md 
- Edit Controller to prevent deprecation warnings

## [0.1.0] - 2019-11-28
### Added
- Add travis build for PHP 7.2 & PHP 7.3
### Changed
- Update symfony dependencies
- Fix phpcs hints in tests
- Update composer.json commands
- Edit the README.md 
### Removed
- Removed support for PHP 7.1

## [0.0.6] - 2019-11-18
### Changed
- Set db field length of url hash to 128 characters

## [0.0.5] - 2019-11-14
### Added
- Add new exception for mutation testing
- Add mutation testing with infection
- Add new unit test for UriManager
### Changed
- Increase the quality of several tests
- Edit the README.md 

## [0.0.4] - 2019-09-24
### Added
- Add the possibility to delete a created short link
### Changed
- Introduce the new token
- Edit the UriManager and Tests
- Edit the UriController and Tests
- Edit the README.md
- Finally use the CHANGELOG.md file

## [0.0.3] - 2019-09-02
### Added
- Add a favicon
- Add the apache package for symfony
### Changed
- Update the readme file to announce the mod_apache package

## [0.0.2] - 2019-08-31
### Changed
- Applied merge requests to simplify the UriController, 
the UriManager and fix some phpdocs 

## [0.0.1] - 2019-06-23 (first release)
### Added
- Add the possibility to create short links
- Add the possibility to use short links
