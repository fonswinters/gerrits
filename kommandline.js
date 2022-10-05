//Require stuff
const readline = require('readline').createInterface({
    input: process.stdin,
    output: process.stdout
});
const { spawn, exec } = require('child_process');
const path = require('path');

/**
 * The main menu
 */
class Menu {
    /**
     * Clear the screen
     */
    static clear() {
        readline.write('\u001B[2J\u001B[0;0f');
    }

    /**
     * Show the main menu
     */
    static mainMenu() {
        let menuChoice = new Promise((resolve, reject) => {
            let prompt = function() {
                Menu.clear();
                readline.write('Welcome to the Kommandline!\nWhat would you like to do? \n');
                readline.write('\n');
                readline.write('1. Setup database\n');
                readline.write('2. Setup testing database\n');
                readline.write('3. Kill artisan serve on port 8000\n');
                readline.write('4. Exit\n');
                readline.write('\n');
                readline.question(`What would you like to do? `, (what) => {
                    let legitOptions = ['1', '2', '3', '4', '5'];

                    if(!legitOptions.includes(what)) {
                        readline.write('That is not a valid option. Try again.\n\n');
                        prompt();
                    }
                    else resolve(what);
                });
            };
            prompt();
        });

        /**
         * Triggered when a choice was made in the main menu
         *
         * @param choice
         */
        function mainMenuCallback(choice) {
            let databaseName;
            switch (choice) {
                case '1':
                    Menu.setupDatabaseMenu().then(() => {
                        readline.write('\n\n\n\n\n');
                        Menu.mainMenu()
                    });
                    break;
                case '2':
                    Menu.setupDatabaseMenu('testing').then(() => {
                        readline.write('\n\n\n\n\n');
                        Menu.mainMenu()
                    });
                    break;
                case '3':
                    Process.killArtisans().then(() => {
                        Menu.mainMenu()
                    }).catch(err => readline.write('\n'+err));
                    break;
                default:
                case '4':
                    process.exit(0);
                    readline.close();
                    break;
            }
        }

        menuChoice.then(mainMenuCallback);
    }

    /**
     * Show a menu to setup the testing or regular database
     * @return {Promise|Promise|Promise}
     */
    static setupDatabaseMenu(databasename = '') {
        return new Promise((resolve, reject) => {
            let prompt = function() {
                Menu.clear();
                readline.write(`These commands wil be executed in order. Is this ok?\n`);
                readline.write(`    php artisan migrate:fresh `+(databasename === 'testing' ? '--env=testing' : '')+`\n`);
                readline.write(`    php artisan kms:seed `+(databasename === 'testing' ? '--env=testing' : '')+`\n`);
                readline.write(`    php artisan db:seed `+(databasename === 'testing' ? '--env=testing' : '')+`\n`);

                readline.write('\n');
                readline.write('1. Yes\n');
                readline.write('2. No\n');
                readline.question(``, (what) => {
                    let legitOptions = ['1', '2'];

                    if(!legitOptions.includes(what)) {
                        Menu.clear();
                        readline.write('That is not a valid option. Try again.\n\n');
                        prompt();
                    }
                    else {
                        if(what === '1') {
                            if (databasename === 'testing') Process.setupTestDatabase().then(() => {
                                Menu.clear();
                                resolve();
                            }).catch(reject);
                            else Process.setupDatabase().then(() => {
                                Menu.clear();
                                resolve()
                            }).catch(reject);
                        } else {
                            resolve();
                        }
                    }
                });
            };
            prompt();
        })
    }

    static getErrorHandler(afterTimeout, showErrorDuration = 5000) {
        return function(error) {
            readline.write('An error occurred\n\n');
            console.error(error);
            setTimeout(afterTimeout, showErrorDuration)
        }
    }
}

/**
 * Knows how to start and handle system processes
 */
class Process {
    /**
     * Truncates the test database. And runs some laravel artisan commands to set it up again.
     * Also does seed the database
     *
     * @return {Promise|Promise|Promise}
     */
    static setupTestDatabase() {
        return Process.setupDatabase(['--env=testing'])
    }

    /**
     * Truncates the database. And runs some laravel artisan commands to set it up again.
     * Also does seed the database
     *
     * @return {Promise|Promise|Promise}
     */
    static setupDatabase(commandlineOptions) {
        commandlineOptions = commandlineOptions || [];

        return new Promise((resolve, reject) => {
            Menu.clear();

            let currentCommandLineOptions = ['artisan', 'migrate:fresh'].concat(commandlineOptions);
            readline.write('\nRunning: php '+currentCommandLineOptions.join(' ')+'\n');
            const migrateCommand = spawn('php', currentCommandLineOptions);
            this.handle(migrateCommand, null, () => {
                currentCommandLineOptions = ['artisan', 'kms:seed'].concat(commandlineOptions);
                readline.write('\nRunning: php '+currentCommandLineOptions.join(' ')+'\n');
                const kmsSeedCommand = spawn('php', currentCommandLineOptions);
                this.handle(kmsSeedCommand, null, () => {
                    currentCommandLineOptions = ['artisan', 'db:seed'].concat(commandlineOptions);
                    readline.write('\nRunning: php '+currentCommandLineOptions.join(' ')+'\n');
                    const seedCommand = spawn('php', currentCommandLineOptions);
                    this.handle(seedCommand, null, resolve, reject)
                }, reject);
            }, reject);
        });
    }

    /**
     * Kill a process on port 8000. Usually php with the artisan serve script
     *
     * @return {Promise|Promise|Promise}
     */
    static killArtisans() {
        return new Promise((resolve, reject) => {
            Menu.clear();
            readline.write('\nStopping everything on port 8000....\n');
            const command = exec('lsof -t -i tcp:8000 | xargs kill');
            this.handle(command, null, () => {
                readline.write('\n\nDone.');
                setTimeout(() => {
                    Menu.clear();
                    resolve()
                }, 2000);
            },reject);
        });
    }

    /**
     * Attaches "listeners" to a process standard in and output streams and
     * a "listener" for when the process closes it.
     *
     * @param process
     * @param name
     * @param closeCallback
     * @param errorCallback
     * @param dataCallback
     */
    static handle(process, name, closeCallback, errorCallback, dataCallback = null) {
        process.stdout.on('data', this.onOutput(name, dataCallback));
        process.stderr.on('data', this.onError(name, errorCallback));
        process.on('close', this.onClose(name, closeCallback));
    }

    /**
     * Returns a function where a process can log to.
     * Readline logs it to the CLI with the name of that process.
     *
     * @param name
     * @param dataCallback
     * @return {Function}
     */
    static onOutput(name, dataCallback) {
        return (outputData) => {
            readline.write((name ? name+':' : '') +' '+outputData+'');
            if(dataCallback) dataCallback(outputData);
        }
    }

    /**
     * Returns a function where a process can log errors to.
     * Readline logs it to the CLI with the name of that process.
     *
     * @param name
     * @param errorCallback
     * @return {Function}
     */
    static onError(name, errorCallback) {
        return (errorData) => {
            readline.write((name ? name+':' : '') +' '+errorData+'');
            if(errorCallback) errorCallback(errorData);
        }
    }

    /**
     * Returns a function where a process can log to when it exits.
     * Readline logs it to the CLI with the name of that process.
     *
     * @param name
     * @param closeCallback
     * @return {Function}
     */
    static onClose(name, closeCallback) {
        return (exitCode) => {
            readline.write((name ? name+':' : '') +' stopped with exit code '+exitCode+'\n');
            if(closeCallback) closeCallback(exitCode);
        }
    }
}



//Show the main menu
Menu.mainMenu();