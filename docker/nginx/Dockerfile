FROM nginx:1.19
LABEL maintainer="Dvet DevOps <contato@dvet.com.br>"

ENV DOCKER_ENTRYPOINT=/docker-entrypoint.sh

# Install additional packages (Debian buster moved to archive)
RUN find /etc/apt -name '*.list' -type f -print -exec sed -i 's|deb.debian.org|archive.debian.org|g; s|security.debian.org|archive.debian.org|g' {} + \
    && printf 'Acquire::Check-Valid-Until "false";\n' > /etc/apt/apt.conf.d/99no-check-valid-until \
    && apt-get update && apt-get install -y \
    curl openssl \
    && apt-get clean

# Copy nginx settings
COPY ./docker/nginx/etc/nginx.conf /etc/nginx/
COPY ./docker/nginx/etc/default-http.conf /etc/nginx/conf.d/default.conf
COPY --chmod=775 ./docker/nginx/scripts/*.sh /

# Add commands to docker-entrypoint.sh
RUN sed -i 's/\r$//' ${DOCKER_ENTRYPOINT} /add-to-entrypoint.sh \
    && echo -n "$(cat ${DOCKER_ENTRYPOINT} /add-to-entrypoint.sh \
    | grep -v "exec \"\$@\"")\n\nexec \"\$@\"\n" \
    > ${DOCKER_ENTRYPOINT}