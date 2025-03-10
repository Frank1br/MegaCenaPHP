# Mega-Sena em PHP

## 📌 Sobre o Projeto
Este projeto é um simulador da **Mega-Sena**, desenvolvido em **PHP** e **Bootstrap**. Ele permite que o usuário escolha **6 números entre 1 e 60**, e então faz um sorteio aleatório, verificando quantos números foram acertados e exibindo o resultado.

## 🎯 Funcionalidades
- Escolha de **6 números distintos** entre **1 e 60**.
- Sorteio automático de **6 números aleatórios**.
- Comparação entre os números apostados e sorteados.
- Exibição do **número de acertos** e **premiação**:
  - **6 acertos** → 🏆 Ganhou na Mega-Sena!
  - **5 acertos** → 🎉 Fez uma Quina!
  - **4 acertos** → 🔥 Fez uma Quadra!
  - **Menos de 4 acertos** → ❌ Não ganhou.
- Interface responsiva utilizando **Bootstrap**.

## 🛠 Tecnologias Utilizadas
- **PHP** (para lógica do sorteio e verificação de acertos)
- **HTML5 e CSS3** (estrutura e estilos básicos)
- **Bootstrap 5** (para um design responsivo)

## 🚀 Como Executar o Projeto
1. **Clone este repositório** ou copie os arquivos para sua máquina.
```sh
 git clone https://github.com/seu-usuario/mega-sena-php.git
```
2. **Inicie um servidor local PHP**.
   - Se você tem o PHP instalado, rode:
   ```sh
   php -S localhost:8000
   ```
   - Ou use um ambiente como **XAMPP** e coloque os arquivos na pasta `htdocs`.
3. **Acesse no navegador**:
   ```sh
   http://localhost:8000
   ```
4. **Escolha 6 números e clique em "Apostar"** para ver os resultados!

## 📂 Estrutura do Projeto
```
📁 mega-sena-php
│-- 📄 index.php       # Página principal com lógica PHP
│-- 📂 img            # Pasta para imagens (ex: logo Mega-Sena)
│-- 📄 README.md       # Documentação do projeto
```

## 🔥 Melhorias Futuras
- Permitir múltiplas apostas ao mesmo tempo.
- Criar um **histórico de apostas**.
- Adicionar **animações e efeitos visuais** usando JavaScript.
- Implementar um **banco de dados** para armazenar apostas passadas.

## 📜 Licença
Este projeto está sob a licença **MIT**. Sinta-se à vontade para usá-lo e modificá-lo! 🎯

---

💡 **Dúvidas ou sugestões?** Fique à vontade para contribuir ou entrar em contato! 🚀

