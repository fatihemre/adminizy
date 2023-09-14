<?php

namespace Eva\Library;

class Breadcrumbs
{
    protected array $elements = [];

    protected string $template = '<li><a href="{{link}}">{{label}}</a></li>';

    protected string $homeLink = '/';
    protected string $homeLabel = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>';

    public function __construct()
    {
        $this->add($this->homeLabel, $this->homeLink);
    }

    public function setHome($label, $link): void
    {
        $this->homeLabel = $label;
        $this->homeLink = $link;
        $this->update($link, ['label' => $this->homeLabel, 'link'=>$this->homeLink]);
    }

    public function add(string $label, string $link): void
    {
        $this->elements[$link] = [
            'label'  => $label,
            'link'   => $link
        ];
    }

    public function output(): string
    {
        if(empty($this->elements)){
            return '';
        }

        $content = '';

        foreach ($this->elements as $key => $crumb) {
            $label = $crumb['label'];

            $htmlCrumb = str_replace(['{{link}}', '{{label}}'], [$crumb['link'], $label], $this->template);

            $this->removeElement($key);
            $content .= $htmlCrumb;
        }

        return '<nav><ol class="breadcrumb">' . $content . '</ol></nav>';
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function removeElement(string $link): self
    {
        if (!empty($this->elements) && array_key_exists($link, $this->elements)) {
            unset($this->elements[$link]);
        }
        return $this;
    }

    public function update(string $link, array $data): self
    {
        if(array_key_exists($link, $this->elements)) {
            $this->elements[$link] = array_merge($this->elements[$link], $data);
        }
        return $this;
    }

    public function count(): int
    {
        return count($this->elements);
    }
}