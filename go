# Update submodules in the root
printf "Updating git submodules in root directory...\n"
git submodule init
git submodule update

# Navigate to the folder where the submodule is, and update further submodules
cd my_files/applications/my-apps/matt/games
printf "Updating git submodules in " 
pwd
printf "...\n";
# I'm doing a git pull here, this is useful in case the code in the submodule repo has changed - rather than just updating it
git pull origin main
git submodule init
git submodule update

# There's some further bash scripts in environment/scripts
cd ../../../../../environment/scripts
echo "Stopping any existing docker instances..."
./stop_all_running_docker
echo "Done.\n\nBuilding new docker..."

# Here I could run the ./build script instead from this folder, but it's only one line so no point, might as well put the real command in here 
docker build -t matt_mysql ../DB
docker build -t matt ../../

echo "Done.\n\nStarting server..."

# For the following 4 lines, there is also a script called local_server in the current folder (/environment/scripts), but I'll list them all out here instead of running it
dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
parentdir="$(dirname "$dir")"
docker network create matt-net
docker run -d --name matt_mysql --network matt-net --env-file $parentdir/environment_variables/mysql.env -p 3306:3306 matt_mysql # For the database
docker run -d --name matt --network matt-net --env-file $parentdir/environment_variables/MATT.env -p 80:80 -p 443:443 -p 444:444 matt /run-httpd.sh

# ./local_server - comented out as i've expanded it all in the 4 lines above

printf "\n\nYour server build has completed and it should now be running. Now navigate to localhost in your browser.\n\n"
printf "You could also log into docker using docker exec -it matt /bin/bash, or log into the mysql container with docker exec -it matt_db mysql -uroot -p\n\n"
