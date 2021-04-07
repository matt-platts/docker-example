# docker-example

### An example of a docker container doing a few things including:

- Passing environment variables into apache which can be picked up in scripts later
- Running apache in a non-default location
- Passing code into docker which contains further gitmodules, themselves containing further git modules

The games submodule was addded with the following code:

git submodule init
git submodule add https://github.com/matt-platts/games.git my_files/applications/my-apps/matt/games

In this submodule the games were added with similar code, documented on the git page at https://github.com/matt-platts/games.

Run the command 'git submodule update' which will download all the nested repos.
