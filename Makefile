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
	@echo "Installing elastic-search..."
	@make -s install-dependencies
	@make -s clear-cache

install-dependencies:
	@sh -c "./build/1-compile.sh"
	@sh -c "./build/2-deploy.sh"

clear-cache:
	@echo "Clearing elasticsearch cache..."
	@sh -c "rm -rf data/cache/module*"
	@sh -c "rm -rf cache/*"

test:
	@echo "Running elasticsearch unit test..."
	@sh -c "./build/3-test.sh"

generate:
	@sh -c "php public/index.php generate"

delete:
	@sh -c "php public/index.php delete"

################################################################################
#                                                                              #
#                               Shortcuts                                      #
#                                                                              #
################################################################################

cc: clear-cache
