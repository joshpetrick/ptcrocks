chaincoind masternode stop 
chaincoind stop
nano ~/chaincoin/chaincoin.conf // change masternode 1 to 0
chaincoind --daemon
watch -n 10 chaincoind masternode start | grep "ready?" // if true continue
chaincoind sendtoaddress <address> <amt>


chaincoind stop
nano ~/chaincoin/chaincoin.conf // change masternode 0 to 10
chaincoind --daemon
watch -n 10 chaincoind masternode start | grep "ready?" // if true continue

