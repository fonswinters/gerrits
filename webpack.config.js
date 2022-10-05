/**
 * The Path module.
 */
const path = require('path');

/**
 * Custom Webpack config.
 *
 * Described here, in the separate file, for phpstorm and other IDE autocomplete support.
 * @see https://gist.github.com/nachodd/4e120492a5ddd56360e8cff9595753ae
 */
module.exports = {
    resolve: {
        alias: {
            /**
             * An alias for KMS core imports.
             *
             * Example usage for javascript:
             * import { bootKms } from 'KMSCore/js/kms'
             *
             * Example usage for sass:
             * @import "~KMSCore/sass/styles.sass"
             */
            '@KMSCore': path.resolve(__dirname, 'vendor/komma/kms/resources/'),
            '@resources': path.resolve(__dirname, './resources'),
        }
    },
    stats: {
        children: true
    }
};