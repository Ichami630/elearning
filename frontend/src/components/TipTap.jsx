import { useEditor, EditorContent } from '@tiptap/react'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link'
import Image from '@tiptap/extension-image'
import Youtube from '@tiptap/extension-youtube'
import TextAlign from '@tiptap/extension-text-align'
import { useEffect } from 'react'

import {
  Bold,
  Italic,
  Underline as UnderlineIcon,
  Strikethrough,
  Heading1,
  Heading2,
  AlignLeft,
  AlignCenter,
  AlignRight,
  AlignJustify,
  List,
  ListOrdered,
  Quote,
  Code,
  Link as LinkIcon,
  Image as ImageIcon,
  Youtube as YoutubeIcon,
  Undo,
  Redo,
  Trash2,
} from 'lucide-react'

const TipTap = ({value,onChange}) => {
  const editor = useEditor({
    extensions: [
      StarterKit,
      Underline,
      Link,
      Image,
      Youtube.configure({
        controls: true,
        width: 640,
        height: 360,
      }),
      TextAlign.configure({
        types: ['heading', 'paragraph'],
      }),
    ],
    content: value || '',
    onUpdate: ({editor}) => {
        onChange(editor.getHTML())
    }
  })

   useEffect(() => {
      if (editor && value) {
        editor.commands.setContent(value)
      }
    }, [value, editor])
  if (!editor) return null

  return (
    <div className="max-w-3xl my-2 text-gray-800">
      {/* Toolbar */}
      <div className="flex flex-wrap gap-2 border border-gray-300 rounded-t-lg p-2 bg-white shadow-sm">
        <ToolbarButton onClick={() => editor.chain().focus().toggleBold().run()} isActive={editor.isActive('bold')} icon={<Bold size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().toggleItalic().run()} isActive={editor.isActive('italic')} icon={<Italic size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().toggleUnderline().run()} isActive={editor.isActive('underline')} icon={<UnderlineIcon size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().toggleStrike().run()} isActive={editor.isActive('strike')} icon={<Strikethrough size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().toggleHeading({ level: 1 }).run()} isActive={editor.isActive('heading', { level: 1 })} icon={<Heading1 size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()} isActive={editor.isActive('heading', { level: 2 })} icon={<Heading2 size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().setTextAlign('left').run()} isActive={editor.isActive({ textAlign: 'left' })} icon={<AlignLeft size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().setTextAlign('center').run()} isActive={editor.isActive({ textAlign: 'center' })} icon={<AlignCenter size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().setTextAlign('right').run()} isActive={editor.isActive({ textAlign: 'right' })} icon={<AlignRight size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().setTextAlign('justify').run()} isActive={editor.isActive({ textAlign: 'justify' })} icon={<AlignJustify size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().toggleBulletList().run()} isActive={editor.isActive('bulletList')} icon={<List size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().toggleOrderedList().run()} isActive={editor.isActive('orderedList')} icon={<ListOrdered size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().toggleBlockquote().run()} isActive={editor.isActive('blockquote')} icon={<Quote size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().toggleCodeBlock().run()} isActive={editor.isActive('codeBlock')} icon={<Code size={18} />} />
        <ToolbarButton
            onClick={() => {
                const previousUrl = editor.getAttributes('link').href
                const url = window.prompt('Enter URL', previousUrl)

                if (url === null) return // user cancelled

                const { empty } = editor.state.selection

                if (empty) {
                // If no text selected, insert the URL as both text and link
                editor
                    .chain()
                    .focus()
                    .insertContent(`<a class="text-blue-300 underline" href="${url}" target="_blank" rel="noopener noreferrer">${url}</a>`)
                    .run()
                } else {
                // If text selected, apply the link to selected text
                editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
                }
            }}
            isActive={editor.isActive('link')}
            icon={<LinkIcon size={18} />}
        />

        <ToolbarButton
          onClick={() => {
            const url = window.prompt('Enter image URL')
            if (url) {
              editor.chain().focus().setImage({ src: url }).run()
            }
          }}
          icon={<ImageIcon size={18} />}
        />
        <ToolbarButton
          onClick={() => {
            const url = window.prompt('Enter YouTube URL')
            if (url) {
              editor.chain().focus().setYoutubeVideo({ src: url }).run()
            }
          }}
          icon={<YoutubeIcon size={18} />}
        />
        <ToolbarButton onClick={() => editor.chain().focus().undo().run()} icon={<Undo size={18} />} />
        <ToolbarButton onClick={() => editor.chain().focus().redo().run()} icon={<Redo size={18} />} />
        <ToolbarButton
          onClick={() => editor.chain().focus().clearNodes().unsetAllMarks().run()}
          icon={<Trash2 size={18} />}
        />
      </div>

      {/* Editor Content */}
      <div className="border border-t-0 border-gray-300 rounded-b-lg w-full p-2 min-h-[200px] bg-white focus:outline-none">
        <EditorContent editor={editor} className="editor-content w-full h-full [&_p]:m-0 [&_p]:text-left" />
      </div>
    </div>
  )
}

// Updated reusable ToolbarButton component
const ToolbarButton = ({ onClick, icon, isActive }) => (
  <button
    type="button"
    onClick={(e) => {
      e.preventDefault()
      onClick()
    }}
    className={`p-1.5 rounded transition-all border border-gray-200 ${
      isActive ? 'bg-blue-400' : 'hover:bg-gray-100 active:bg-blue-400'
    }`}
  >
    {icon}
  </button>
)

export default TipTap
