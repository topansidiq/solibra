import "tailwindcss";
import Alpine from "alpinejs";
import { createIcons, icons } from "lucide";

window.Alpine = Alpine;
Alpine.start();

createIcons({ icons });
