PHP=$(shell which php)
BOX=$(shell which php-box)
COMPOSER=$(shell which composer)

build: composer_install generate_classes build_box

composer_install:
	$(COMPOSER) install --no-dev --prefer-dist

generate_classes:
	rm -rf var/classes/*
	$(PHP) vendor/bin/autocodeloader.php var/classes src

build_box:
	$(BOX) build
