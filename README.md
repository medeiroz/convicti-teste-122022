# Summary
- [Install WSL](#install-wsl)
- [Run Application](#run-application)
- [IDE Open Project](#ide-open-project)
- [Api Documentation](#api-documentation)


# Install WSL
This step needed only for windows

## Configure WSL
### Open administrator powershell

Enabled WSL
```powershell
dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
```

Restart your pc

Upgrade WSL

```powershell
dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart
```

Download and install update

[https://wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi](https://wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi)

Set default wsl to new version
```powershell
wsl --set-default-version 2
```

Install last ubuntu subsystem version on Microsoft Store

[Click here to install Ubuntu sub system](https://www.microsoft.com/store/productId/9NBLGGH4MSV6)

Set ubuntu to new version
```powershell
wsl --set-version Ubuntu 2
```

## Install Windows Terminal
This step needed only for windows

[Windows terminal](https://docs.microsoft.com/en-us/windows/terminal/get-started)

## Open Terminal on Ubuntu

### Configure terminal and alias
#### Open `.bashrc` file and append alias/export
```bash
vim ~/.bashrc
```
#### Create Sail alias
```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
alias sa='sail artisan'
```
#### Show current Branch name
```bash
parse_git_branch() {
     git branch 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/(\1)/'
}
export PS1="\[\e]0;\u@\h: \w\a\]${debian_chroot:+($debian_chroot)}\[\033[01;32m\]\u@\h\[\033[00m\]:\[\033[01;34m\]\w \[\e[91m\]\$(parse_git_branch)\[\033[00m\]\n$ "
```
Refresh terminal

```bash
source ~/.bashrc
```

### Make new folder and follow
```bash
mkdir ~/code && cd ~/code
```

### Clone Project
```bash
git clone git@github.com:medeiroz/convicti-test-122022.git
```

```bash
cd convicti-test-122022
```

Configure git user
```bash
git config user.name "Flavio Medeiros"
```
```bash
git config user.email "smedeiros.flavio@gmail.com"
```

Copy .env file
```bash
cp .env.example .env
```

Fill variables in .env


### Install dependencies
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

# Run Application
```bash
sail up -d
```

### Create APP key
```
sail artisan key:generate
```

### Run migrations and seed
```bash
sail artisan migrate --seed
```


# IDE Open Project
#### VSCODE
- Open VScode
- Install Remote - WSL extension
- Restart vscode
- File > Open Folder
- `Ctrl` + `L` or tab on path input
- Write `\\wsl$` + `Enter`
- Navigate to Ubuntu > home > YourUserName > code > convicti-test-122022
- Select Folder

#### PHPSTORM
- Open PHPStorm
- File > Open...
- Write or Select folder
- Navigate to `\\wsl$` > Ubuntu > home > YourUserName > code > convicti-test-122022
- Select Folder


# API Documentation
### Generate Documentation
```bash
sail artisan scribe:generate
```
### Access
[https://localhost/docs](https://localhost/docs)
