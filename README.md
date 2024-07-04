# PHP Virus Simulation Script

This PHP script is designed to simulate a virus by recursively infecting PHP files within a directory. The script includes functions to execute a payload, update code if a new version is released, run commands from a server, and encrypt content.

## Features

- Payload Execution: Infects PHP files with a checksum and an encrypted payload.
- Code Update: Updates infected files if a new version is detected.
- Command Execution: Runs commands from a server, which are specified in a data.json file.
- Content Encryption: Encrypts the virus content before injecting it into the target files.

## Files

- data.json: Contains version information and command list to be executed.
- update.json: Contains update data for the infected files.

## Functions

- executeCode($virus): Infects PHP files by injecting an encrypted payload and a checksum.
- updateCode(): Updates infected files if a new version is available, based on the update.json file.
- runCommands(): Runs commands from the data.json file. Updates the code if a newer version is found.
- encryptContent($virus): Encrypts the virus content using AES-256-CBC encryption.
- glob_recursive($pattern, $flags = 0): Recursively retrieves files matching a pattern.

## How It Works

1. Initialization: The script reads its own content and extracts the portion between // BOOM:START and // BOOM:END.
2. File Infection: It scans for PHP files recursively, skipping itself, and injects the extracted payload along with a checksum.
3. Command Execution: Periodically checks data.json for commands to execute and updates itself if a new version is available.
4. Encryption: Encrypts the payload before injection to obfuscate its content.

## Usage

1. Place the script in the root directory of your PHP project.
2. Create data.json and update.json files with appropriate content.
3. Run the script.

### Example data.json
```json
{
  "version": "1.0.0",
  "active": true,
  "command_list": [
    "echo 'Executing command 1';",
    "echo 'Executing command 2';"
  ]
}
```
### Example update.json
```json
{
  "data": [
    "<?php // Update code here ?>",
    "<?php echo 'New version'; ?>"
  ]
}
```
## Security Notice

This script is for educational purposes only. It simulates a virus-like behavior to demonstrate how malicious code can propagate and execute commands. **Do not use this script in a production environment or on systems where you do not have permission to test.**

## License

This project is licensed under the MIT License - see the LICENSE file for details
