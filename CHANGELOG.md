# Changelog

## [Unreleased](https://github.com/org/repo/compare/0.10.0...master)

## [0.10.0](https://github.com/org/repo/compare/0.9.1...0.10.0) - 2022-11-10

### Changed

- Refactor opening and closing time attribute for readability (#14)
- Return full date for upcoming opening times (#15)
- Use import instead of require (#16)

## [0.9.1](https://github.com/org/repo/compare/0.9.0...0.9.1) - 2022-09-01

### Fixed

- Re-add times to the frontend.retailers (#13)

## [0.9.0](https://github.com/org/repo/compare/0.8.0...0.9.0) - 2022-08-26

### Changed

- Get upcoming opening time with PHP (#12)

## [0.8.0](https://github.com/org/repo/compare/0.7.4...0.8.0) - 2022-07-07

### Added

- Include the phone number by default (eb9cc05)

## [0.7.4](https://github.com/org/repo/compare/0.7.3...0.7.4) - 2022-06-24

### Fixed

- Fix for if the store is closed this day but opens in the next days (#10)

## [0.7.3](https://github.com/org/repo/compare/0.7.2...0.7.3) - 2022-06-23

### Fixed

- Fix wrong day + check if value exists (#9)

## [0.7.2](https://github.com/org/repo/compare/0.7.1...0.7.2) - 2022-06-23

### Fixed

- Always parse day_of_week to int (#8)

## [0.7.1](https://github.com/org/repo/compare/0.7.0...0.7.1) - 2022-06-23

### Fixed

- Wrong day fix (#7)

## [0.7.0](https://github.com/org/repo/compare/0.6.6...0.7.0) - 2022-06-19

### Added

- Show opening times if closed (#6)

## [0.6.6](https://github.com/org/repo/compare/0.6.5...0.6.6) - 2022-06-08

### Fixed

- Just a version bump

## [0.6.5](https://github.com/org/repo/compare/0.6.4...0.6.5) - 2022-06-07

### Fixed

- Wait for maps to be loaded, useful for lazy loading (9fe73cd)

## [0.6.4](https://github.com/org/repo/compare/0.6.3...0.6.4) - 2022-05-20

### Fixed

- Overview open when not yet opened fix (5963053)

## [0.6.3](https://github.com/org/repo/compare/0.6.2...0.6.3) - 2022-05-10

### Fixed

- Fix time formatting for timezones and updated lodash to window.debounce (#5)

## [0.6.2](https://github.com/org/repo/compare/0.6.1...0.6.2) - 2022-04-26

### Fixed

- Opening time check (#4)

## [0.6.1](https://github.com/org/repo/compare/0.6.0...0.6.1) - 2022-04-12

### Fixed

- Use the new core facade location (3814224)

## [0.6.0](https://github.com/org/repo/compare/0.5.0...0.6.0) - 2022-04-11

### Changed

- Laravel 9 compatibility (3348f31)

## [0.5.0](https://github.com/org/repo/compare/0.4.4...0.5.0) - 2022-02-15

### Changed

- Sort locations based on distance (#2)

## [0.4.4](https://github.com/org/repo/compare/0.4.3...0.4.4) - 2021-12-16

### Fixed

- Cast the date to Y-m-d when it is serialized (1a4b2c7)

## [0.4.3](https://github.com/org/repo/compare/0.4.2...0.4.3) - 2021-12-09

### Fixed

- Show the correct special openinghour times (d6ab36f)

## [0.4.2](https://github.com/org/repo/compare/0.4.1...0.4.2) - 2021-10-13

### Changed

- Moved the openinghours to a partial (2392d0f)

## [0.4.1](https://github.com/org/repo/compare/0.4.0...0.4.1) - 2021-10-13

### Fixed

- Show a 404 when a retailer does not exist (fbd09ac)
- Only show active stores (90c5201)

## [0.4.0](https://github.com/org/repo/compare/0.3.0...0.4.0) - 2021-08-25

### Added

- Autofocus, autoselect, canonical and prefill (f79fe1f)

## [0.3.0](https://github.com/org/repo/compare/0.2.0...0.3.0) - 2021-08-12

### Added

- Current location button (bccd379)

## [0.2.0](https://github.com/org/repo/compare/0.1.1...0.2.0) - 2021-08-05

### Added

- Show opening hours on the overview (942f24e)
- Map search (f72933a)
- Show when a shop is open (af8ae2e)

## [0.1.1](https://github.com/org/repo/compare/0.1.0...0.1.1) - 2021-08-04

### Changed

- Changed the `rapidez/core` version constraint (ff93bc6)

## [0.1.0](https://github.com/org/repo/compare/e7610614aa4203a154b7e5dc3249c1ddb69a48a7...0.1.0) - 2021-07-24

Initial release
