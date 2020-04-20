# BitShares SDK for PHP Apps

* Integrate BitShares (or other cryptocurrency chain on BitShares protocol)


## Installation

`composer require furqansiddiqui/bitshares-php`

## Integration

After compiling/install BitShares, for Wallet API, program `cli_wallet` has to be launched. 
It is recommended to launch `cli_wallet` program in `tmux` session.  

**Command:**  
`./cli_wallet --chain-id= --server-rpc-endpoint=ws://localhost:8092 -r 0.0.0.0:8090`

Parameter | Argument Type | Example Value
--- | --- | ---
--server-rpc-endpoint= | Hostname and port where to launch RPC server; Must also specify web-socket protocol either (`ws` or `wss`) | ws://localhost:8092
-r | IP and port where `witness_node` program is running | 0.0.0.0:8096

