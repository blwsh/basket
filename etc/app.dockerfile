# An exmple of a multi-stage Dockerfile - Designed to produce a minimal size
# production ready Docker image.

# Build the base image. This is the foundation for the application runtime.
# We only need to install what is needed for the runtime here and nothing else.
# PHP extensions required only to build should be done in the next stage named
# `composer`
FROM php:8-cli-alpine AS base
USER www-data
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /src
COPY --chown=www-data:www-data . /src

# Build time dependencies and packages should be installed here.
FROM base AS dependencies
RUN composer install

# And finally, we build the final image.
# We copy in dependencies and packages from build stages here or anything else required for the run time.

# Note: Never include credentials in a docker image (not even build stages)! These should always be included
# via volume mounts or retrieved via some sort of credentials mechanism.
FROM base
COPY ./etc/nginx.conf /etc/nginx/conf.d/basket.dev.conf
COPY ./etc/php.ini /usr/local/php/conf.d/basket.dev.ini
COPY --from=dependencies /src/vendor /src/vendor
ENTRYPOINT composer run test

