# Security Policy

## Supported Versions

| Version | Supported          |
|---------|--------------------|
| 2.x     | :white_check_mark: |
| 1.x     | :x:                |

## Reporting a Vulnerability

We take security vulnerabilities seriously. If you discover a security issue, please report it responsibly.

### How to Report

**Please do NOT report security vulnerabilities through public GitHub issues.**

Instead, send an email to **[security@oxygen.gr](mailto:security@oxygen.gr)** with:

1. **Description** - A clear description of the vulnerability
2. **Steps to Reproduce** - Detailed steps to reproduce the issue
3. **Impact** - The potential impact of the vulnerability
4. **Affected Versions** - Which versions are affected
5. **Suggested Fix** - If you have a suggestion for how to fix the issue (optional)

### What to Expect

- **Acknowledgment**: We will acknowledge receipt of your report within 48 hours
- **Assessment**: We will assess the vulnerability and determine its severity
- **Updates**: We will keep you informed of our progress
- **Resolution**: We aim to resolve critical vulnerabilities within 7 days
- **Credit**: We will credit you in the security advisory (unless you prefer to remain anonymous)

### Disclosure Policy

- We follow a coordinated disclosure process
- We will work with you to understand and resolve the issue
- We request that you give us reasonable time to address the vulnerability before public disclosure
- We will publicly disclose the vulnerability once a fix is available

## Security Best Practices

When using this package, please follow these security recommendations:

### Token Storage

1. **Never commit credentials** - Keep your ERGANI username and password out of version control
2. **Use environment variables** - Store credentials in environment variables or a secure secrets manager
3. **Secure cache directory** - If using `FileToken`, configure the cache directory outside the web root:

```php
$options = ['cache_dir' => '/var/app/storage/tokens'];
Token::setCurrentTokenManager(new FileToken($username, $password, $options), $env);
```

### Web Server Configuration

If using the default `.cache/` directory, ensure your web server blocks access:

**Nginx:**
```nginx
location ~ /\.cache {
    deny all;
}
```

**Apache:** The package includes an `.htaccess` file in `.cache/` for automatic protection.

### Production Environment

- Always use `Environment::PRODUCTION` for production systems
- Implement proper error handling to avoid exposing sensitive information
- Monitor and rotate tokens regularly
- Use HTTPS for all communications (the ERGANI API enforces this)

## Dependencies

This package uses:
- [Guzzle](https://github.com/guzzle/guzzle) for HTTP requests
- Development dependencies are checked against [Roave Security Advisories](https://github.com/Roave/SecurityAdvisories)

We monitor our dependencies for known vulnerabilities and update them promptly.
