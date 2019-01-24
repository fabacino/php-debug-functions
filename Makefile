all: coverage

test:
	vendor/bin/phpunit

coverage:
	vendor/bin/phpunit --coverage-text

.PHONY: test coverage
