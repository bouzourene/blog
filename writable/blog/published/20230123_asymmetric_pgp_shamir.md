<!--
TITLE: Achieve asymmetric encryption with PGP and Shamir's secret sharing
AUTHOR: William Bouzourène
-->
Here's a use-case for such a concept: you need to backup some sensitive data offsite or even offline, but you also need the guarantee than someone cannot just make a copy of it and decrypt it on his own.

### Why PGP ?
Because it's a great way to do asymmetric encryption and if fits our use-case pretty well (you can encrypt both text and binary data). PGP won't be enough for this tough, because there's only one private key and one secret.

By using asymmetric encryption, we can encrypt data on the fly, without ever needing anything else than the public key.

### Why Shamir's secret sharing ?
Because it's the most common way to do split secrets and there are already a lot of libraries for it. It will allow us to distribute keys across a pool of trusted users and define a threshold of those users to get the complete key.

Example: I generate 4 keys and require that at least 2 of the users provide their key in order to decrypt the sensitive archive.

### Don't roll your own crypto!
It's a general rule in software development, prefer using trusted librairies maintained by security/crypto professionals. In my case, I ended up using Proton's PGP Go lib and Hashicorp's Shamir's secret sharing Go lib (used by Hashicorp Vault).

### My implementation
I wrote the software in Go, as you could've guessed from the libs I picked. It's available on my GitHub profile: [Byoki](https://github.com/bouzourene/byoki). I also have a mirror on my [GitLab instance](https://git.readonly.ch/github-mirror/byoki).

### Step 1: Create the keys payload
This will create a JSON file: it will contain a public key, an encrypted private key, as well as some useful metadata.

```
./byoki genkeys -m <shamir threshold, ex: 2> -t <shamir total, ex: 4> -o <path to output file, ex: keys.json>
```

![step1](https://i.imgur.com/AwgPKFy.png)

### Step 2: Encrypt a file or an archive with our newly created keys
```
./byoki encrypt -k <path to our keys file, ex: keys.json> -f <path to the file to encrypt, ex: archive.zip>
```

![step2](https://i.imgur.com/NCZkG9o.png)

### Step 3: Decrypt our archive with the threshold amount of Shamir secrets
```
./byoki decrypt -a <path to the encrypted archive, ex: archive-xyz.byoki>
```
![step3](https://i.imgur.com/qlSN3Gn.png)
