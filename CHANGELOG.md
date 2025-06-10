# Changelog 

[Unreleased changes](https://github.com/rapidez/smile-store-locator/compare/4.0.0...4.0.0)
## [4.0.0](https://github.com/rapidez/smile-store-locator/releases/tag/4.0.0) - 2025-06-10

### Added

- Rapidez v4 support + opening time edge case fix (#34)

## [2.1.0](https://github.com/rapidez/smile-store-locator/releases/tag/2.1.0) - 2024-07-24

### Added

- Register routes config (#32)

## [2.0.1](https://github.com/rapidez/smile-store-locator/releases/tag/2.0.1) - 2024-06-10

### Fixed

- Fix broken packages:discover without database (#31)


## [2.0.0](https://github.com/rapidez/smile-store-locator/releases/tag/2.0.0) - 2024-05-28

### Added

- Laravel 11 support (#30)

## [0.17.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.17.0) - 2024-03-21

### Added

- Rapidez v2 support (#29)

## [0.16.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.16.0) - 2023-10-31

### Added

- Rapidez v1 support (#28)

## [0.15.1](https://github.com/rapidez/smile-store-locator/releases/tag/0.15.1) - 2023-09-20

### Changed

- Prevent error if bounds are unavailable (#27)

## [0.15.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.15.0) - 2023-09-19

### Changed

- Get Current Location async (#26)

## [0.14.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.14.0) - 2023-08-29

### Added

- Retailer distance (#25)

## [0.13.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.13.0) - 2023-08-04

### Changed

- Heroicons v2 update (#24)

## [0.12.1](https://github.com/rapidez/smile-store-locator/releases/tag/0.12.1) - 2023-07-04

### Fixed

- Test action fix (#22)
- Use url helpers (#23)

## [0.12.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.12.0) - 2023-04-05

### Added

- Add package.js (#18)

### Changed

- Remove references to lodash (#19)

## [0.11.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.11.0) - 2023-01-25

### Changed

- Updated gmap-vue (#17)
- Add changelog action (b940074)

## [0.10.1](https://github.com/rapidez/smile-store-locator/releases/tag/0.10.1) - 2022-12-15

### Fixed

- Removed unused import (22186b0)

## [0.10.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.10.0) - 2022-11-10

### Changed

- Refactor opening and closing time attribute for readability (#14)
- Return full date for upcoming opening times (#15)
- Use import instead of require (#16)

## [0.9.1](https://github.com/rapidez/smile-store-locator/releases/tag/0.9.1) - 2022-09-01

### Fixed

- Re-add times to the frontend.retailers (#13)

## [0.9.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.9.0) - 2022-08-26

### Changed

- Get upcoming opening time with PHP (#12)

## [0.8.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.8.0) - 2022-07-07

### Added

- Include the phone number by default (eb9cc05)

## [0.7.4](https://github.com/rapidez/smile-store-locator/releases/tag/0.7.4) - 2022-06-24

### Fixed

- Fix for if the store is closed this day but opens in the next days (#10)

## [0.7.3](https://github.com/rapidez/smile-store-locator/releases/tag/0.7.3) - 2022-06-23

### Fixed

- Fix wrong day + check if value exists (#9)

## [0.7.2](https://github.com/rapidez/smile-store-locator/releases/tag/0.7.2) - 2022-06-23

### Fixed

- Always parse day_of_week to int (#8)

## [0.7.1](https://github.com/rapidez/smile-store-locator/releases/tag/0.7.1) - 2022-06-23

### Fixed

- Wrong day fix (#7)

## [0.7.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.7.0) - 2022-06-19

### Added

- Show opening times if closed (#6)

## [0.6.6](https://github.com/rapidez/smile-store-locator/releases/tag/0.6.6) - 2022-06-08

### Fixed

- Just a version bump as there where some issues with 0.6.5

## [0.6.4](https://github.com/rapidez/smile-store-locator/releases/tag/0.6.4) - 2022-05-20

### Fixed

- Overview open when not yet opened fix (5963053)

## [0.6.3](https://github.com/rapidez/smile-store-locator/releases/tag/0.6.3) - 2022-05-10

### Fixed

- Fix time formatting for timezones and updated lodash to window.debounce (#5)

## [0.6.2](https://github.com/rapidez/smile-store-locator/releases/tag/0.6.2) - 2022-04-26

### Fixed

- Opening time check (#4)

## [0.6.1](https://github.com/rapidez/smile-store-locator/releases/tag/0.6.1) - 2022-04-12

### Fixed

- Use the new core facade location (3814224)

## [0.6.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.6.0) - 2022-04-11

### Changed

- Laravel 9 compatibility (3348f31)

## [0.5.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.5.0) - 2022-02-15

### Changed

- Sort locations based on distance (#2)

## [0.4.4](https://github.com/rapidez/smile-store-locator/releases/tag/0.4.4) - 2021-12-16

### Fixed

- Cast the date to Y-m-d when it is serialized (1a4b2c7)

## [0.4.3](https://github.com/rapidez/smile-store-locator/releases/tag/0.4.3) - 2021-12-09

### Fixed

- Show the correct special openinghour times (d6ab36f)

## [0.4.2](https://github.com/rapidez/smile-store-locator/releases/tag/0.4.2) - 2021-10-13

### Changed

- Moved the openinghours to a partial (2392d0f)

## [0.4.1](https://github.com/rapidez/smile-store-locator/releases/tag/0.4.1) - 2021-10-13

### Fixed

- Show a 404 when a retailer does not exist (fbd09ac)
- Only show active stores (90c5201)

## [0.4.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.4.0) - 2021-08-25

### Added

- Autofocus, autoselect, canonical and prefill (f79fe1f)

## [0.3.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.3.0) - 2021-08-12

### Added

- Current location button (bccd379)

## [0.2.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.2.0) - 2021-08-05

### Added

- Show opening hours on the overview (942f24e)
- Map search (f72933a)
- Show when a shop is open (af8ae2e)

## [0.1.1](https://github.com/rapidez/smile-store-locator/releases/tag/0.1.1) - 2021-08-04

### Changed

- Changed the `rapidez/core` version constraint (ff93bc6)

## [0.1.0](https://github.com/rapidez/smile-store-locator/releases/tag/0.1.0) - 2021-07-24

Initial release

