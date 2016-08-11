################################################################################
################################################################################
#                                                                              #
#                                  Variables                                   #
#                                                                              #
################################################################################

.DEFAULT: build

################################################################################
#                                                                              #
#                               Public Commands                                #
#                                                                              #
################################################################################

build: install

install:
	@echo "Installing..."
	@sh -c "./build/1-compile.sh"
	@sh -c "./build/2-deploy.sh"
	@sh -c "./build/3-test.sh"
	@make -s clear-cache

clear-cache:
	@echo "Clearing cache..."
	@sh -c "rm -rf data/cache/module*"
	@sh -c "rm -rf cache/*"

test:
	@echo "Running unit test..."
	@sh -c "./build/3-test.sh"

generate:
	@sh -c "php public/index.php generate"

import:
	@echo "Importing Month -1..."
	@sh -c "php public/index.php import --month=1"
	@echo "Importing Month -2..."
	@sh -c "php public/index.php import --month=2"
	@echo "Importing Month -3..."
	@sh -c "php public/index.php import --month=3"
	@echo "Importing Month -4..."
	@sh -c "php public/index.php import --month=4"
	@echo "Importing Month -5..."
	@sh -c "php public/index.php import --month=5"
	@echo "Importing Month -6..."
	@sh -c "php public/index.php import --month=6"
	@echo "Importing Month -7..."
	@sh -c "php public/index.php import --month=7"
	@echo "Importing Month -8..."
	@sh -c "php public/index.php import --month=8"
	@echo "Importing Month -9..."
	@sh -c "php public/index.php import --month=9"
	@echo "Importing Month -10..."
	@sh -c "php public/index.php import --month=10"
	@echo "Importing Month -11..."
	@sh -c "php public/index.php import --month=11"
	@echo "Importing Month -12..."
	@sh -c "php public/index.php import --month=12"

delete:
	@sh -c "php public/index.php delete"

delete-all:
	@sh -c "php public/index.php delete-all"

################################################################################
#                                                                              #
#                               Shortcuts                                      #
#                                                                              #
################################################################################

cc: clear-cache
