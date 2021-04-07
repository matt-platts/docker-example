# docker-example

### An example of a docker container doing a few things including:

- Passing environment variables into apache which can be picked up in scripts later
- Running apache in a non-default location
- Passing code into docker which contains further gitmodules, themselves containing further git modules

## Instructions

1. Clone this git repo (via ssh or https).
2. cd into the repo and download the git modules and subsequent modules.

  - cd docker-example
  - git submodule init
  - git submodule update
  - cd my_files/applications/my-apps/matt/games
  - git submodule init
  - git submodule update

3. cd to envionment/scripts from the git root and run the build script ('./build' - note this is a bash script so won't work on Windows - or might with git bash installed?), or simply run 'docker build -t matt ../../' from within this folder.
4. Staying in this folder, run the script 'local_server' (or for windows, open it and run each line individually)
5. Navigate to localhost in your browser and be amazed!


## Notes on the git modules:

The games submodule was addded with the following code:

- git submodule init
- git submodule add https://github.com/matt-platts/games.git my_files/applications/my-apps/matt/games

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

