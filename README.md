# docker-example

### An example of a docker container doing a few things including:

- Passing environment variables into apache which can be picked up in scripts later
- Running apache in a non-default location
- Passing code into docker which contains further gitmodules, themselves containing further git modules

The games submodule was addded with the following code:

git submodule init
git submodule add https://github.com/matt-platts/games.git my_files/applications/my-apps/matt/games

From within this submodule, the games were added with similar code, documented on the git page at https://github.com/matt-platts/games.

Run the command 'git submodule update' which will download the initial repo.
Then go into this repo at my_files/applications/my-apps/matt/games and run the following (git responses are included in the below):

- git submodule init
  - Submodule 'battleships' (https://github.com/matt-platts/battleships.git) registered for path 'battleships'
  - Submodule 'pacman-2016' (https://github.com/matt-platts/pacman-2016.git) registered for path 'pacman'
- mattplatts@C02C342JMD6T games % git submodule update
  - Cloning into '/Users/mattplatts/Docker/MATT/my_files/applications/my-apps/matt/games/battleships'...
  - Cloning into '/Users/mattplatts/Docker/MATT/my_files/applications/my-apps/matt/games/pacman'...
  - Submodule path 'battleships': checked out '01827e7ea42db76bfa4b8373854db90c24cef28e'
  - Submodule path 'pacman': checked out '92279d0f277dbb379c267f36c18c1282a543c1c0'

