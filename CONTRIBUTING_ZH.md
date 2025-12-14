# 貢獻指南

感謝您有興趣為 `{{PACKAGE_NAME}}` 做出貢獻！我們歡迎社群的貢獻。

[English](./CONTRIBUTING.md) | 繁體中文

## 行為準則

參與本專案，即表示您同意為所有人維護一個尊重和包容的環境。

## 如何貢獻

### 回報錯誤

1. **搜尋現有 issues** 避免重複
2. **使用 bug 回報模板** 建立新 issue
3. **包含** PHP 版本、套件版本和重現步驟

### 建議功能

1. **搜尋現有 issues** 查看是否已有人提議
2. **使用功能請求模板** 建立新 issue
3. **說明使用案例** 以及為什麼這會有幫助

### Pull Requests

1. **Fork 儲存庫** 並建立新分支
2. **遵循程式碼規範** (PSR-12)
3. **為新功能撰寫測試**
4. **更新文件** 如有需要
5. **提交 pull request** 並附上清楚的說明

## 開發環境設定

### 先決條件

- PHP 8.1+
- Composer

### 安裝

```bash
git clone https://github.com/{{REPO_OWNER}}/{{REPO_NAME}}.git
cd {{REPO_NAME}}
composer install
```

### 執行測試

```bash
# 執行所有測試
composer test

# 執行測試並產生覆蓋率報告
composer test:coverage
```

## 程式碼規範

本專案遵循 **PSR-12** 程式碼規範。

### 程式碼風格

- 使用嚴格型別：`declare(strict_types=1);`
- 適當使用 readonly 屬性
- 為所有公開方法加上 PHPDoc
- 保持方法專注且簡短

### 範例

```php
<?php

declare(strict_types=1);

namespace {{NAMESPACE}};

/**
 * 示範程式碼規範的範例類別。
 */
final class Example
{
    /**
     * 建立新實例。
     *
     * @param string $value 要儲存的值
     */
    public function __construct(
        public readonly string $value
    ) {
    }

    /**
     * 取得格式化的值。
     *
     * @return string 格式化的值
     */
    public function getFormatted(): string
    {
        return strtoupper($this->value);
    }
}
```

## Commit 訊息

我們遵循 [Conventional Commits](https://www.conventionalcommits.org/)：

- `feat:` 新功能
- `fix:` 錯誤修復
- `docs:` 文件變更
- `test:` 測試新增或修改
- `refactor:` 程式碼重構
- `chore:` 維護任務

### 範例

```
feat: 新增自訂 HTTP 客戶端支援
fix: 正確處理空的回應主體
docs: 更新安裝說明
test: 新增驗證例外的測試
```

## 發布流程

當推送版本標籤時，會透過 GitHub Actions 自動發布：

```bash
git tag v1.0.0
git push origin v1.0.0
```

---

感謝您為 `{{PACKAGE_NAME}}` 做出貢獻！🎉
