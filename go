printf "Updating git submodules in root directory...\n"
git submodule init
git submodule update
cd my_files/applications/my-apps/matt/games
printf "Updating git submodules in " 
pwd
printf "...\n";
git pull origin main
git submodule init
git submodule update
cd ../../../../../environment/scripts
echo "Stopping any existing docker instances..."
./stop_all_running_docker
echo "Done.\n\nBuilding new docker..."
./build
echo "Done.\n\nStarting server..."
./local_server
printf "\n\nYour server build has completed and it should now be running. Now navigate to localhost in your browser.\n\n"

