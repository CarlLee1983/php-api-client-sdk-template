# {{PACKAGE_NAME}}

[![CI](https://github.com/{{REPO_OWNER}}/{{REPO_NAME}}/actions/workflows/ci.yml/badge.svg)](https://github.com/{{REPO_OWNER}}/{{REPO_NAME}}/actions/workflows/ci.yml)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

[English](./README.md) | 繁體中文

{{PACKAGE_DESCRIPTION}}

## 特色

- 🚀 **PHP 8.1+** 嚴格型別與唯讀屬性
- 📦 **PSR-4 自動載入** 使用 `{{NAMESPACE}}` 命名空間
- 🔌 **可注入的 HTTP 客戶端** 便於模擬和測試
- ✅ **型別安全的配置** 含驗證功能
- 🛡️ **完整的錯誤處理** 自訂例外類別
- 🎯 **Laravel 支援** 自動發現
- 📝 **完整的 PHPDoc 文件** 支援 IDE 自動完成

## 系統需求

- PHP 8.1 或更高版本
- ext-json

## 安裝

```bash
composer require {{PACKAGE_NAME}}
```

## 快速開始

### 基本設定

```php
use {{NAMESPACE}}\Config;
use {{NAMESPACE}}\Client;

$client = new Client(new Config(
    apiKey: getenv('API_KEY'),
    baseUri: 'https://api.example.com'
));
```

### 發送請求

```php
// 您的 SDK 使用範例
$response = $client->someMethod();
```

## Laravel 整合

此套件支援 Laravel 自動發現。安裝後，服務提供者會自動註冊。

### 發布設定檔

```bash
php artisan vendor:publish --provider="{{NAMESPACE}}\Laravel\ServiceProvider"
```

### 環境設定

在 `.env` 檔案中加入以下設定：

```env
SDK_API_KEY=your-api-key
SDK_BASE_URI=https://api.example.com
SDK_SANDBOX=true
```

### 在 Laravel 中使用

```php
use {{NAMESPACE}}\Client;

class YourController
{
    public function __construct(private Client $client)
    {
    }

    public function index()
    {
        return $this->client->someMethod();
    }
}
```

## 錯誤處理

```php
use {{NAMESPACE}}\Exception\HttpException;
use {{NAMESPACE}}\Exception\ValidationException;
use {{NAMESPACE}}\Exception\ConfigException;

try {
    $response = $client->someMethod();
} catch (ValidationException $e) {
    // 處理驗證錯誤
    foreach ($e->getErrors() as $field => $errors) {
        echo "{$field}: " . implode(', ', $errors) . "\n";
    }
} catch (HttpException $e) {
    // 處理 HTTP 錯誤
    echo "HTTP 錯誤 {$e->getStatusCode()}: {$e->getMessage()}\n";
} catch (ConfigException $e) {
    // 處理配置錯誤
    echo "配置錯誤: {$e->getMessage()}\n";
}
```

## 測試

此函式庫包含可注入的 HTTP 客戶端介面，便於測試：

```php
use {{NAMESPACE}}\Http\HttpClientInterface;
use {{NAMESPACE}}\Http\HttpResponse;

$mockClient = new class implements HttpClientInterface {
    public function request(
        string $method,
        string $url,
        array $headers = [],
        array $body = []
    ): HttpResponse {
        return new HttpResponse(200, json_encode([
            'status' => 'success',
        ]));
    }
};

$client = new Client($config, $mockClient);
```

## 執行測試

```bash
composer install
composer test
```

## 文件

詳細 API 參考，請參閱 [API 文件](./docs/API.md)。

## 貢獻

詳情請參閱 [CONTRIBUTING.md](./CONTRIBUTING.md)。

## 安全性

關於安全漏洞，請參閱 [SECURITY.md](./SECURITY.md)。

## 授權

本專案採用 MIT 授權條款 - 詳情請參閱 [LICENSE](./LICENSE) 檔案。

## 連結

- [GitHub 儲存庫](https://github.com/{{REPO_OWNER}}/{{REPO_NAME}})
- [Packagist](https://packagist.org/packages/{{PACKAGE_NAME}})
