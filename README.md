# BitShares SDK for PHP Apps

* Integrate BitShares (or other cryptocurrency chain on BitShares protocol)


## Installation

`composer require furqansiddiqui/bitshares-php`

## Integration

After compiling/install BitShares, for Wallet API, program `cli_wallet` has to be launched. 
It is recommended to launch `cli_wallet` program in `tmux` session.  

**Command:**  
`./cli_wallet --server-rpc-endpoint=ws://localhost:8092 -r 0.0.0.0:8090`

Parameter | Example Value | Argument Type
--- | --- | ---
--server-rpc-endpoint= | ws://localhost:8092 | Hostname and port where to launch RPC server; Must also specify web-socket protocol either (`ws` or `wss`)
-r | 0.0.0.0:8090 | IP and port where `witness_node` program is running

### Important Notes

* **Unlocked Wallet Requirement:** Any transaction that requires use of a private key will require an unlocked wallet. A wallet maybe unlocked from CLI for an indefinite amount of time.
* **Import Necessary Accounts:** To be able to register and create accounts, a pre-existing account is required with sufficient balance to be able to register/create new accounts thus 
making it near mandatory to import at least one account to your BTS wallet. API method `importAccount` may be used for this purpose.

# Examples

