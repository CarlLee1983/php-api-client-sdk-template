# PHP API Client SDK Template

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://www.php.net/)

[English](./TEMPLATE_README.md) | 繁體中文

一個用於建立 **API 客戶端函式庫** 的 PHP SDK 模板。使用此模板快速建立與第三方 API 整合的 SDK 專案（支付閘道、SaaS 服務等）。

## 特色

- 🚀 **PHP 8.1+** 嚴格型別與唯讀屬性
- 📦 **PSR-4 自動載入** 預先配置
- 🔌 **可注入的 HTTP 客戶端** 便於測試
- ✅ **型別安全基類** (BaseClient, BaseConfig)
- 🛡️ **例外階層** (Base, Config, Http, Validation)
- 🎯 **Laravel 支援** ServiceProvider 和 Facade
- 🧪 **PHPUnit 測試** 預先配置
- 🔄 **GitHub Actions** CI/CD 自動化
- 📝 **文件模板** (README, CONTRIBUTING, SECURITY)

## 適用場景

此模板設計用於建立以下類型的 SDK：

- ✅ 與 **REST API** 整合（支付閘道、SaaS 服務）
- ✅ 需要 **HTTP 客戶端** 功能
- ✅ 需要 **配置管理**（API 金鑰、基礎 URL）
- ✅ 支援 **Laravel** 整合
- ✅ 遵循 **PSR 標準**

**範例**：TapPay SDK、LINE Pay SDK、Stripe SDK、Twilio SDK 等。

## 快速開始

### 1. 複製或下載

```bash
# 複製模板
git clone https://github.com/CarlLee1983/php-api-client-sdk-template.git my-new-sdk
cd my-new-sdk
```

### 2. 執行初始化腳本

```bash
./init.sh
```

腳本會引導您完成：
- Composer vendor 名稱（例如 `carllee1983`）
- 套件名稱（例如 `my-sdk`）
- 套件描述
- PHP 命名空間（例如 `Vendor\MySdk`）
- GitHub 用戶名稱（預設為 vendor 名稱）
- 儲存庫名稱（預設為套件名稱）
- 作者資訊
- 是否包含 Laravel 支援

### 3. 安裝依賴

```bash
composer install
```

### 4. 執行測試

```bash
composer test
```

## 包含內容

### 基類

| 類別 | 說明 |
|------|-----|
| `BaseClient` | HTTP 客戶端，包含 GET, POST, PUT, DELETE 方法 |
| `BaseConfig` | 配置基類，含驗證功能 |
| `ConfigInterface` | 配置類別的契約介面 |

### 例外類別

| 例外 | 說明 |
|-----|-----|
| `BaseException` | 所有 SDK 錯誤的基礎例外 |
| `ConfigException` | 配置驗證錯誤 |
| `HttpException` | HTTP 請求/回應錯誤 |
| `ValidationException` | 輸入驗證錯誤 |

### HTTP 抽象層

| 類別 | 說明 |
|-----|-----|
| `HttpClientInterface` | 可注入的 HTTP 客戶端介面 |
| `HttpResponse` | HTTP 回應封裝 |
| `NativeHttpClient` | 預設實作（無依賴） |

### Laravel 整合

| 類別 | 說明 |
|-----|-----|
| `ServiceProvider` | 自動發現服務提供者 |
| `Facade` | Facade 基類 |
| `config/sdk.php` | 配置檔案 |

## 模板變數

初始化時會替換以下佔位符：

| 變數 | 說明 | 範例 |
|----|-----|-----|
| `{{PACKAGE_NAME}}` | Composer 套件名稱 | `carllee1983/my-sdk` |
| `{{PACKAGE_DESCRIPTION}}` | 套件描述 | `A PHP SDK for...` |
| `{{NAMESPACE}}` | PHP 命名空間 | `MyCompany\MySdk` |
| `{{NAMESPACE_ESCAPED}}` | JSON 轉義版本 | `MyCompany\\MySdk` |
| `{{REPO_OWNER}}` | GitHub 用戶名稱 | `CarlLee1983` |
| `{{REPO_NAME}}` | 儲存庫名稱 | `my-sdk-php` |
| `{{AUTHOR_NAME}}` | 作者姓名 | `Carl Lee` |
| `{{AUTHOR_EMAIL}}` | 作者信箱 | `carl@example.com` |
| `{{YEAR}}` | 當前年份 | `2024` |

## 專案結構

```
php-api-client-sdk-template/
├── .github/
│   ├── ISSUE_TEMPLATE/
│   │   ├── bug_report.yml
│   │   ├── config.yml
│   │   └── feature_request.yml
│   └── PULL_REQUEST_TEMPLATE.md
│
├── templates/                     # CI 檔案（初始化時複製）
│   └── .github/
│       ├── workflows/
│       │   ├── ci.yml
│       │   └── release.yml
│       └── dependabot.yml
│
├── config/
│   └── sdk.php                    # Laravel 配置
│
├── src/
│   ├── Contracts/
│   │   └── ConfigInterface.php
│   ├── Exception/
│   │   ├── BaseException.php
│   │   ├── ConfigException.php
│   │   ├── HttpException.php
│   │   └── ValidationException.php
│   ├── Http/
│   │   ├── HttpClientInterface.php
│   │   ├── HttpResponse.php
│   │   └── NativeHttpClient.php
│   ├── Laravel/
│   │   ├── Facade.php
│   │   └── ServiceProvider.php
│   ├── BaseClient.php
│   └── BaseConfig.php
│
├── tests/
│   ├── Unit/
│   │   ├── BaseClientTest.php
│   │   ├── ExceptionTest.php
│   │   └── HttpResponseTest.php
│   └── TestCase.php
│
├── .gitignore
├── CHANGELOG.md
├── CONTRIBUTING.md
├── CONTRIBUTING_ZH.md
├── LICENSE
├── README.md
├── README_ZH.md
├── SECURITY.md
├── TEMPLATE_README.md             # 模板使用指南（初始化後移除）
├── TEMPLATE_README_ZH.md          # 模板使用指南 - 中文
├── composer.json
├── init.sh                        # 初始化腳本
└── phpunit.xml
```

> **注意**：`templates/` 目錄包含 GitHub CI/CD 工作流程，會在初始化時複製到 `.github/`。這可以避免模板倉庫本身的 CI 失敗。

## 初始化後

執行 `init.sh` 後，您需要：

1. **實作您的 SDK**
   - 建立繼承 `BaseConfig` 的 `Config` 類別
   - 建立繼承 `BaseClient` 的 `Client` 類別
   - 新增 API 方法

2. **更新文件**
   - 更新 README 包含實際使用範例
   - 記錄您的 API 方法

3. **在 Packagist 註冊**
   - 提交至 https://packagist.org/packages/submit

## 範例：建立簡單的 SDK

初始化後，以下是建立 SDK 的方式：

```php
<?php
// src/Config.php

declare(strict_types=1);

namespace MyCompany\MySdk;

use MyCompany\MySdk\Exception\ConfigException;

final class Config extends BaseConfig
{
    public function __construct(
        public readonly string $apiKey,
        string $baseUri = 'https://api.example.com',
        bool $sandbox = true
    ) {
        if (empty(trim($apiKey))) {
            throw new ConfigException('API key is required');
        }
        parent::__construct($baseUri, $sandbox);
    }
}
```

```php
<?php
// src/Client.php

declare(strict_types=1);

namespace MyCompany\MySdk;

final class Client extends BaseClient
{
    public function getUsers(): array
    {
        return $this->get('/users', [], [
            'Authorization' => 'Bearer ' . $this->config->apiKey,
        ]);
    }

    public function createUser(array $data): array
    {
        return $this->postJson('/users', $data, [
            'Authorization' => 'Bearer ' . $this->config->apiKey,
        ]);
    }
}
```

## 貢獻

歡迎改進此模板！請參閱 [CONTRIBUTING.md](./CONTRIBUTING.md)。

## 授權

此模板採用 MIT 授權條款 - 詳情請參閱 [LICENSE](./LICENSE) 檔案。

## 作者

由 [Carl Lee](https://github.com/CarlLee1983) 建立
